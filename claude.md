# Frontrow — Project Memory
> Keep this file updated as the project evolves. It is the single source of truth for context.

---

## Project Identity
- **App name:** Frontrow
- **Type:** Laravel 12 multi-role SaaS platform (tech services company)
- **PHP:** 8.2 | **Laravel:** 12 | **Fortify:** v1.37.0
- **DB:** MySQL | **Cache driver:** database (Redis-ready, see notes)
- **Frontend:** Bootstrap 5.3 + custom CSS variables + Font Awesome 6 + DM Sans / Playfair Display
- **Local dev:** Laragon (Windows), path `C:\laragon\www\react-app`

---

## Authentication Stack
| Package | Role |
|---|---|
| Laravel Fortify v1.37 | Headless auth backend |
| Spatie Laravel Permission | Role & permission management |
| Custom `LoginResponse` | Role-aware post-login redirect |
| Custom `RedirectIfAuthenticated` | Role-aware redirect for already-authenticated users |
| Custom `TrafficController` middleware | Self-healing navigation + cache-control headers |

### Fortify Views registered in `FortifyServiceProvider::boot()`
- `auth.login`, `auth.register`, `auth.verify-email`
- `auth.forgot-password`, `auth.reset-password` (passes `$token` and `$email` explicitly)
- `auth.confirm-password`, `auth.two-factor-challenge`

### Known Fortify gotchas resolved
- **Do NOT call** `Fortify::redirectUserForTwoFactorAuthenticationUsing()` — it re-registers the default and crashes the container on artisan boot.
- **Do NOT call** `$middleware->redirectAuthenticatedTo()` in `bootstrap/app.php` — the method does not exist on the `Middleware` config class in Laravel 12.
- `use Throwable;` at the top of `bootstrap/app.php` causes a PHP warning — use the fully qualified `\Throwable` inline instead.
- The passkeys rate limiter calls `$request->session()` which throws during CLI boot — only add it when implementing WebAuthn.

---

## Roles & Redirects
| Role | Login Redirect | Route Prefix | Sidebar Partial |
|---|---|---|---|
| `super-admin` | `/super-admin/dashboard` | `super-admin.*` | `partials.sidebar.super-admin` |
| `admin` | `/admin/dashboard` | `admin.*` | `partials.sidebar.admin` |
| `agent` | `/agent/dashboard` | `agent.*` | `partials.sidebar.agent` |
| `user` | `/dashboard` | (none) | `partials.sidebar.user` |

Role resolution priority order in `TrafficController::$roleMap`:
`super-admin` → `admin` → `agent` → `user`

---

## File Structure (key files)
```
app/
├── Console/Commands/
│   └── ArchiveSecurityLogs.php          — monthly archive job
├── Http/
│   ├── Controllers/
│   │   ├── DashboardController.php      — default user dashboard
│   │   ├── SettingsController.php       — shared settings (all roles)
│   │   ├── Admin/DashboardController.php
│   │   ├── Agent/DashboardController.php
│   │   └── SuperAdmin/
│   │       ├── DashboardController.php
│   │       ├── SecurityMonitoringController.php
│   │       └── AcknowledgeAlertController.php
│   ├── Middleware/
│   │   ├── RedirectIfAuthenticated.php  — role-aware guest redirect
│   │   ├── SecurityHeaders.php          — OWASP security headers
│   │   └── TrafficController.php        — self-heal nav + cache-control
│   └── Responses/
│       └── LoginResponse.php            — role-aware post-login redirect
├── Listeners/Auth/
│   ├── LogSuccessfulLogin.php
│   ├── LogFailedLogin.php
│   ├── LogLogout.php
│   ├── LogLockout.php
│   ├── LogPasswordReset.php
│   └── LogRegistered.php
├── Models/
│   ├── User.php                         — HasRoles, MustVerifyEmail, custom notifications
│   ├── SecurityLog.php                  — append-only, no updated_at
│   └── SecurityAlert.php               — append-only except acknowledged_at/by
├── Notifications/
│   └── SecurityAlertNotification.php   — email alert, Slack-ready
├── Providers/
│   ├── AppServiceProvider.php           — registers all auth event listeners
│   └── FortifyServiceProvider.php       — Fortify config + view bindings
└── Services/
    ├── SecurityLogger.php               — single logging source of truth
    └── SecurityDashboardService.php     — all dashboard queries (cached 60s)

config/
├── security.php                         — thresholds, alert email, db_events list, archive days
└── logging.php                          — `security` channel: daily JSON, storage/logs/security/

database/migrations/
├── users, password_reset_tokens, sessions (standard)
├── spatie permission tables
├── xxxx_create_security_logs_table.php
└── xxxx_create_security_alerts_table.php

resources/views/
├── layouts/
│   ├── app.blade.php                    — authenticated master layout
│   └── guest.blade.php                  — unauthenticated auth layout
├── partials/sidebar/
│   ├── super-admin.blade.php
│   ├── admin.blade.php
│   ├── agent.blade.php
│   └── user.blade.php
├── auth/
│   ├── login.blade.php
│   ├── register.blade.php
│   ├── forgot-password.blade.php
│   ├── reset-password.blade.php         — uses $token and $email (not $request)
│   ├── verify-email.blade.php
│   ├── confirm-password.blade.php
│   └── two-factor-challenge.blade.php
├── super-admin/
│   ├── dashboard.blade.php
│   └── security/
│       ├── index.blade.php              — monitoring page
│       └── ip-detail.blade.php          — IP investigation
├── admin/dashboard.blade.php
├── agent/dashboard.blade.php
├── dashboard.blade.php                  — default user
├── settings/index.blade.php            — shared, tabs filtered by role
└── welcome.blade.php                    — public homepage (Frontrow tech company)

routes/
├── web.php
└── console.php                          — archive schedule (1st of month 02:00)

bootstrap/
└── app.php                              — middleware config + global exception handler
```

---

## Security Architecture (OWASP compliant)

### Logging flow
1. All events → `SecurityLogger` service (one entry point)
2. Every event → `storage/logs/security/security.log` (JSON, 90-day rotation)
3. High-value events only → `security_logs` DB table (see `config/security.php` `db_events`)
4. Threshold breaches → `security_alerts` DB table + email to `SECURITY_ALERT_EMAIL`

### High-value DB events
`auth.login_failed`, `auth.lockout`, `auth.password_reset_requested`,
`auth.password_reset_success`, `auth.registered`, `access.denied`,
`access.control_violation`, `error.unhandled_exception`, `security.alert_triggered`

### OWASP thresholds (defaults)
| Trigger | Count | Window |
|---|---|---|
| Failed logins per IP | 5 | 1 min |
| Lockouts per IP | 3 | 60 min |
| Access violations per IP | 10 | 10 min |

### Alert deduplication
One alert email per IP per alert type per threshold window (cache key:
`security:alert_sent:{type}:{ip}`).

### Immutability
- `SecurityLog`: no updates, no deletes (boot hooks throw `LogicException`)
- `SecurityAlert`: only `acknowledged_at` and `acknowledged_by` may be updated

### Database maintenance
- Records older than 90 days moved to `security_logs_archive` by
  `php artisan security:archive-logs` (scheduled monthly)

---

## Environment Variables Required
```env
APP_DEBUG=false                          # production
APP_KEY=                                 # php artisan key:generate
SECURITY_ALERT_EMAIL=                    # security alert recipient
CACHE_DRIVER=database                    # switch to redis when ready
DB_CONNECTION=mysql
SEED_PASSWORD=                           # overrides default seed password
```

---

## Patterns & Conventions
- **Controllers are thin** — business logic lives in Service classes
- **No `$request->all()`** for mass assignment — always `validated()` or `only()`
- **No raw column names from user input** — always whitelist validated
- **Blade uses `{{ }}`** — `{!! !!}` only on pre-sanitised trusted content
- **`basename()`** on all user-supplied file path inputs
- **CSRF** on all POST forms via `@csrf`
- **`verified` middleware** on all authenticated route groups
- **`TrafficController`** applied once at the top of the authenticated group — not repeated per-role

## Frontend Conventions
- Bootstrap 5.3 grid for layout, CSS variables for all colours/spacing
- No Tailwind (no build step required)
- Color scheme: navy `#0b0f1a` background, gold `#c9a84c` accent
- Fonts: Playfair Display (headings), DM Sans (body)
- Sidebar: 260px expanded / 72px collapsed (desktop), off-canvas overlay (mobile)
- Sidebar state persisted to `localStorage` key `fr_sidebar`
- Settings tabs gated by Spatie `@role` / `@hasanyrole` directives

---

## Seeder Accounts (local only, never production)
| Name | Email | Role |
|---|---|---|
| Super Jackson | superadmin@demo.com | super-admin |
| Admin User | admin@demo.com | admin |
| Agent User | agent@demo.com | agent |
| Shedrack Bisala | bisala@demo.com | user |

Password: set via `SEED_PASSWORD` in `.env` (default: `Ch@ngeMe2025!`)

---

## Pending / Not Yet Implemented
- Settings routes (placeholder structure in place, `SettingsController` stub created)
- User management pages (super-admin)
- Admin → Agent/User management pages
- Agent ticket/client pages
- Profile update, password change forms (settings wired to views, not yet to controllers)
- Notification preference persistence
- Integrations (structure only)
- Billing (structure only)
- 2FA enable/disable UI flow (Fortify backend ready, UI buttons in settings)

---

## Redis Migration Notes (when ready)
1. `composer require predis/predis`
2. `.env`: `CACHE_DRIVER=redis`, `REDIS_HOST=127.0.0.1`, `REDIS_PORT=6379`
3. No code changes needed — `Cache::` facade is driver-agnostic

## Slack Alert Migration Notes (when ready)
1. `composer require laravel/slack-notification-channel`
2. `.env`: `SLACK_SECURITY_WEBHOOK_URL=https://hooks.slack.com/...`
3. In `SecurityAlertNotification`: add `'slack'` to `via()`, add `toSlack()` method

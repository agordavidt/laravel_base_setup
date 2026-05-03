@extends('layouts.app')
@section('title', 'Settings')

@push('styles')
<style>
    .form-label-fr {
        font-size: .78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: var(--text-muted);
        margin-bottom: .4rem;
    }
    .form-control-fr {
        border: 1px solid var(--border);
        border-radius: .45rem;
        padding: .55rem .85rem;
        font-size: .875rem;
        font-family: 'DM Sans', sans-serif;
        width: 100%;
        background: var(--surface);
        color: var(--text-primary);
        transition: border-color .15s, box-shadow .15s;
        outline: none;
    }
    .form-control-fr:focus {
        border-color: var(--gold);
        box-shadow: 0 0 0 3px rgba(201,168,76,.12);
    }
    .account-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 0;
        border-bottom: 1px solid var(--border);
        gap: 1rem;
        flex-wrap: wrap;
    }
    .account-row:last-child { border-bottom: none; }
    .account-row-info h5 {
        font-size: .9rem; font-weight: 600; margin: 0 0 .15rem;
    }
    .account-row-info p {
        font-size: .8rem; color: var(--text-muted); margin: 0;
    }
    .toggle-wrap { position: relative; display: inline-block; width: 44px; height: 24px; }
    .toggle-wrap input { opacity: 0; width: 0; height: 0; }
    .toggle-slider {
        position: absolute; inset: 0;
        background: var(--border);
        border-radius: 24px;
        cursor: pointer;
        transition: background .2s;
    }
    .toggle-slider::before {
        content: '';
        position: absolute;
        height: 18px; width: 18px;
        left: 3px; top: 3px;
        background: #fff;
        border-radius: 50%;
        transition: transform .2s;
        box-shadow: 0 1px 3px rgba(0,0,0,.2);
    }
    .toggle-wrap input:checked + .toggle-slider { background: var(--gold); }
    .toggle-wrap input:checked + .toggle-slider::before { transform: translateX(20px); }
</style>
@endpush

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb"><li class="breadcrumb-item">Home</li><li class="breadcrumb-item active">Settings</li></ol>
        </nav>
        <h1>Settings</h1>
    </div>
</div>

<div class="content-card">

    {{-- Tab Nav --}}
    <div class="settings-tabs-nav" id="settingsTabs">
        <button class="settings-tab-btn active" data-tab="profile">
            <i class="fas fa-user"></i> Profile
        </button>
        <button class="settings-tab-btn" data-tab="account">
            <i class="fas fa-lock"></i> Account
        </button>
        <button class="settings-tab-btn" data-tab="preferences">
            <i class="fas fa-sliders"></i> Preferences
        </button>
        <button class="settings-tab-btn" data-tab="notifications">
            <i class="fas fa-bell"></i> Notifications
        </button>
        @hasanyrole('super-admin|admin')
        <button class="settings-tab-btn" data-tab="integrations">
            <i class="fas fa-plug"></i> Integrations
        </button>
        @endhasanyrole
        @hasanyrole('super-admin')
        <button class="settings-tab-btn" data-tab="billing">
            <i class="fas fa-credit-card"></i> Billing
        </button>
        @endhasanyrole
    </div>

    {{-- ── Profile ─────────────────────────────────────────────────────── --}}
    <div class="settings-panel active" id="tab-profile">
        <div class="row g-4">
            <div class="col-12 col-md-3 text-center">
                <div style="width:90px;height:90px;border-radius:50%;background:var(--gold);color:var(--navy);display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:700;margin:0 auto 1rem;">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                </div>
                <button class="btn-ghost w-100 mb-2"><i class="fas fa-upload me-1"></i> Upload Photo</button>
                <button class="btn-ghost w-100" style="color:var(--danger);border-color:var(--danger);font-size:.8rem;">Remove</button>
            </div>
            <div class="col-12 col-md-9">
                <form method="POST" action="#">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <label class="form-label-fr">Full Name</label>
                            <input type="text" class="form-control-fr" name="name"
                                   value="{{ old('name', auth()->user()->name) }}">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label-fr">Email Address</label>
                            <input type="email" class="form-control-fr" name="email"
                                   value="{{ old('email', auth()->user()->email) }}">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label-fr">Phone Number</label>
                            <input type="tel" class="form-control-fr" name="phone" placeholder="+1 (555) 000-0000">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label-fr">Role</label>
                            <input type="text" class="form-control-fr" disabled
                                   value="{{ str_replace('-', ' ', ucwords(auth()->user()->getRoleNames()->first() ?? 'User')) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label-fr">Bio</label>
                            <textarea class="form-control-fr" name="bio" rows="3"
                                      placeholder="Tell us a little about yourself…"></textarea>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn-gold">Save Changes</button>
                        <button type="button" class="btn-ghost">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Account ─────────────────────────────────────────────────────── --}}
    <div class="settings-panel" id="tab-account">

        <div class="account-row">
            <div class="account-row-info">
                <h5>Change Password</h5>
                <p>Update your password to keep your account secure</p>
            </div>
            <button class="btn-ghost">Change</button>
        </div>

        <div class="account-row">
            <div class="account-row-info">
                <h5>Two-Factor Authentication</h5>
                <p>Add an extra layer of security with TOTP authentication</p>
            </div>
            <div>
                @if (auth()->user()->two_factor_confirmed_at)
                    <form method="POST" action="/user/two-factor-authentication">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-ghost" style="color:var(--danger);border-color:var(--danger);">
                            Disable 2FA
                        </button>
                    </form>
                @else
                    <form method="POST" action="/user/two-factor-authentication">
                        @csrf
                        <button type="submit" class="btn-gold">Enable 2FA</button>
                    </form>
                @endif
            </div>
        </div>

        <div class="account-row">
            <div class="account-row-info">
                <h5>Email Verification</h5>
                <p>{{ auth()->user()->email_verified_at ? 'Verified on ' . auth()->user()->email_verified_at->format('d M Y') : 'Your email has not been verified yet' }}</p>
            </div>
            @if (!auth()->user()->email_verified_at)
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn-gold">Resend Verification</button>
                </form>
            @else
                <span class="status-badge badge-success">Verified</span>
            @endif
        </div>

        <div class="account-row" style="border-top:1px solid var(--border);margin-top:1rem;padding-top:1.5rem;">
            <div class="account-row-info">
                <h5 style="color:var(--danger);">Delete Account</h5>
                <p>Permanently delete your account and all associated data. This cannot be undone.</p>
            </div>
            <button class="btn-ghost" style="color:var(--danger);border-color:var(--danger);">Delete Account</button>
        </div>

    </div>

    {{-- ── Preferences ─────────────────────────────────────────────────── --}}
    <div class="settings-panel" id="tab-preferences">
        <form method="POST" action="#">
            @csrf @method('PUT')
            <div class="row g-4">
                <div class="col-12 col-sm-6">
                    <label class="form-label-fr">Theme</label>
                    <select class="form-control-fr">
                        <option>Light</option>
                        <option>Dark</option>
                        <option>System</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6">
                    <label class="form-label-fr">Language</label>
                    <select class="form-control-fr">
                        <option>English</option>
                        <option>Spanish</option>
                        <option>French</option>
                        <option>German</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6">
                    <label class="form-label-fr">Timezone</label>
                    <select class="form-control-fr">
                        <option>UTC+0 (London)</option>
                        <option>UTC+1 (Lagos / Paris)</option>
                        <option>UTC-5 (Eastern)</option>
                        <option>UTC-8 (Pacific)</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6">
                    <label class="form-label-fr">Date Format</label>
                    <select class="form-control-fr">
                        <option>DD/MM/YYYY</option>
                        <option>MM/DD/YYYY</option>
                        <option>YYYY-MM-DD</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn-gold">Save Preferences</button>
            </div>
        </form>
    </div>

    {{-- ── Notifications ────────────────────────────────────────────────── --}}
    <div class="settings-panel" id="tab-notifications">

        <p style="font-size:.82rem;color:var(--text-muted);margin-bottom:1.5rem;">
            Choose which notifications you receive. Security alerts cannot be disabled.
        </p>

        @php
            $notifs = [
                ['id' => 'notif_security',  'label' => 'Security Alerts',        'desc' => 'Login attempts, lockouts, and suspicious activity',          'checked' => true,  'disabled' => true],
                ['id' => 'notif_email_ver', 'label' => 'Email Verification',      'desc' => 'Emails related to account verification and password resets',  'checked' => true,  'disabled' => false],
                ['id' => 'notif_login',     'label' => 'New Login Notification',  'desc' => 'Get notified when your account is accessed from a new device','checked' => true,  'disabled' => false],
                ['id' => 'notif_product',   'label' => 'Product Updates',         'desc' => 'News about new features and platform improvements',           'checked' => false, 'disabled' => false],
                ['id' => 'notif_weekly',    'label' => 'Weekly Summary',          'desc' => 'A weekly summary of your account activity',                   'checked' => false, 'disabled' => false],
            ];
        @endphp

        <form method="POST" action="#">
            @csrf @method('PUT')
            @foreach ($notifs as $n)
                <div class="account-row">
                    <div class="account-row-info">
                        <h5>
                            {{ $n['label'] }}
                            @if($n['disabled'])
                                <span class="status-badge badge-gold ms-2" style="font-size:.65rem;">Required</span>
                            @endif
                        </h5>
                        <p>{{ $n['desc'] }}</p>
                    </div>
                    <label class="toggle-wrap">
                        <input type="checkbox" name="{{ $n['id'] }}" {{ $n['checked'] ? 'checked' : '' }} {{ $n['disabled'] ? 'disabled' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            @endforeach
            <div class="mt-4">
                <button type="submit" class="btn-gold">Save Preferences</button>
            </div>
        </form>
    </div>

    {{-- ── Integrations (admin+) ────────────────────────────────────────── --}}
    @hasanyrole('super-admin|admin')
    <div class="settings-panel" id="tab-integrations">
        @php
            $integrations = [
                ['name' => 'Slack',            'icon' => 'fab fa-slack',   'connected' => false, 'desc' => 'Receive security and system alerts in Slack'],
                ['name' => 'Google Analytics', 'icon' => 'fab fa-google',  'connected' => false, 'desc' => 'Track platform usage analytics'],
                ['name' => 'GitHub',           'icon' => 'fab fa-github',  'connected' => false, 'desc' => 'Connect your deployment repository'],
                ['name' => 'AWS',              'icon' => 'fab fa-aws',     'connected' => false, 'desc' => 'Cloud storage and infrastructure'],
            ];
        @endphp
        <div class="row g-3">
            @foreach ($integrations as $int)
            <div class="col-12 col-md-6">
                <div style="border:1px solid var(--border);border-radius:.65rem;padding:1.1rem;display:flex;align-items:center;gap:1rem;">
                    <div style="width:42px;height:42px;background:var(--bg);border-radius:.5rem;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">
                        <i class="{{ $int['icon'] }}" style="color:var(--text-secondary);"></i>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:600;font-size:.88rem;">{{ $int['name'] }}</div>
                        <div style="font-size:.77rem;color:var(--text-muted);">{{ $int['desc'] }}</div>
                    </div>
                    @if ($int['connected'])
                        <button class="btn-ghost" style="font-size:.78rem;padding:.35rem .8rem;color:var(--danger);border-color:var(--danger);">Disconnect</button>
                    @else
                        <button class="btn-gold" style="font-size:.78rem;padding:.35rem .8rem;flex-shrink:0;">Connect</button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endhasanyrole

    {{-- ── Billing (super-admin only) ──────────────────────────────────── --}}
    @role('super-admin')
    <div class="settings-panel" id="tab-billing">
        <div class="row g-4">
            <div class="col-12 col-md-5">
                <div style="border:1px solid var(--gold);border-radius:.75rem;padding:1.4rem;background:rgba(201,168,76,.04);">
                    <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.1em;color:var(--gold);margin-bottom:.5rem;">Current Plan</div>
                    <div style="font-size:1.4rem;font-weight:700;margin-bottom:.25rem;">Professional</div>
                    <div style="font-size:1.8rem;font-weight:700;color:var(--gold);">$99 <span style="font-size:.9rem;font-weight:400;color:var(--text-muted);">/ month</span></div>
                    <hr style="border-color:var(--border);">
                    <div class="d-flex flex-column gap-2">
                        @foreach(['Unlimited users','Advanced analytics','Priority support','Custom integrations'] as $feat)
                            <div style="font-size:.83rem;display:flex;align-items:center;gap:.5rem;">
                                <i class="fas fa-check" style="color:var(--success);"></i> {{ $feat }}
                            </div>
                        @endforeach
                    </div>
                    <button class="btn-gold w-100 mt-3">Upgrade Plan</button>
                </div>
            </div>
            <div class="col-12 col-md-7">
                <h5 style="font-size:.9rem;font-weight:700;margin-bottom:1rem;">Billing History</h5>
                <table class="fr-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ([['Mar 14, 2025','Professional Plan'],['Feb 14, 2025','Professional Plan'],['Jan 14, 2025','Professional Plan']] as $inv)
                        <tr>
                            <td style="color:var(--text-muted);font-size:.82rem;">{{ $inv[0] }}</td>
                            <td>{{ $inv[1] }}</td>
                            <td style="font-weight:600;">$99.00</td>
                            <td><a href="#" style="font-size:.78rem;color:var(--gold);">Download</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="margin-top:1.5rem;">
                    <h5 style="font-size:.9rem;font-weight:700;margin-bottom:.75rem;">Payment Method</h5>
                    <div style="display:flex;align-items:center;gap:.75rem;padding:.85rem 1rem;border:1px solid var(--border);border-radius:.5rem;">
                        <i class="fab fa-cc-visa" style="font-size:1.6rem;color:#1a1f71;"></i>
                        <span style="font-size:.88rem;">Visa ending in 4242</span>
                        <button class="btn-ghost ms-auto" style="font-size:.78rem;padding:.35rem .8rem;">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endrole

</div>

@endsection

@push('scripts')
<script>
document.querySelectorAll('.settings-tab-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const tab = this.dataset.tab;

        document.querySelectorAll('.settings-tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.settings-panel').forEach(p => p.classList.remove('active'));

        this.classList.add('active');
        const panel = document.getElementById('tab-' + tab);
        if (panel) panel.classList.add('active');
    });
});
</script>
@endpush

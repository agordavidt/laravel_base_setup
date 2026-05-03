{{-- Super Admin Navigation --}}
<div class="nav-section-label">Main</div>

<a href="{{ route('super-admin.dashboard') }}"
   class="nav-item {{ request()->routeIs('super-admin.dashboard') ? 'active' : '' }}"
   >
    <i class="fas fa-th-large"></i>
    <span>Dashboard</span>
</a>

<a href="{{ route('super-admin.security.index') }}"
   class="nav-item {{ request()->routeIs('super-admin.security.*') ? 'active' : '' }}"
   data-label="Security">
    <i class="fas fa-shield-halved"></i>
    <span>Security</span>
    @php $alertCount = \App\Models\SecurityAlert::whereNull('acknowledged_at')->count(); @endphp
    @if ($alertCount > 0)
        <span class="nav-badge">{{ $alertCount > 99 ? '99+' : $alertCount }}</span>
    @endif
</a>

<div class="nav-section-label">Management</div>

<a href="#" class="nav-item {{ request()->routeIs('super-admin.users.*') ? 'active' : '' }}" data-label="Users">
    <i class="fas fa-users"></i>
    <span>Users</span>
</a>

<a href="#" class="nav-item {{ request()->routeIs('super-admin.roles.*') ? 'active' : '' }}" data-label="Roles">
    <i class="fas fa-user-shield"></i>
    <span>Roles & Permissions</span>
</a>

<a href="#" class="nav-item" data-label="Admins">
    <i class="fas fa-user-tie"></i>
    <span>Admins</span>
</a>

<a href="#" class="nav-item" data-label="Agents">
    <i class="fas fa-headset"></i>
    <span>Agents</span>
</a>

<div class="nav-section-label">System</div>

<a href="#" class="nav-item" data-label="Audit Logs">
    <i class="fas fa-file-lines"></i>
    <span>Audit Logs</span>
</a>

<a href="#" class="nav-item" data-label="System Health">
    <i class="fas fa-heart-pulse"></i>
    <span>System Health</span>
</a>

{{-- <a href=""
   class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}"
   data-label="Settings">
    <i class="fas fa-gear"></i>
    <span>Settings</span>
</a> --}}

{{-- Admin Navigation --}}
<div class="nav-section-label">Main</div>

<a href="{{ route('admin.dashboard') }}"
   class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
   data-label="Dashboard">
    <i class="fas fa-th-large"></i>
    <span>Dashboard</span>
</a>

<div class="nav-section-label">Management</div>

<a href="#" class="nav-item" data-label="Agents">
    <i class="fas fa-headset"></i>
    <span>Agents</span>
</a>

<a href="#" class="nav-item" data-label="Users">
    <i class="fas fa-users"></i>
    <span>Users</span>
</a>

<a href="#" class="nav-item" data-label="Reports">
    <i class="fas fa-chart-line"></i>
    <span>Reports</span>
</a>

<div class="nav-section-label">Account</div>

<a href=""
   class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}"
   data-label="Settings">
    <i class="fas fa-gear"></i>
    <span>Settings</span>
</a>
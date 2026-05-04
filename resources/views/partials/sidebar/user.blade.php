{{-- User Navigation --}}
<div class="nav-section-label">Main</div>

<a href="{{ route('dashboard') }}"
   class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
   data-label="Dashboard">
    <i class="fas fa-th-large"></i>
    <span>Dashboard</span>
</a>

<div class="nav-section-label">Account</div>

<a href=""
   class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}"
   data-label="Settings">
    <i class="fas fa-gear"></i>
    <span>Settings</span>
</a>
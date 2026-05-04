{{-- Agent Navigation --}}
<div class="nav-section-label">Main</div>

<a href="{{ route('agent.dashboard') }}"
   class="nav-item {{ request()->routeIs('agent.dashboard') ? 'active' : '' }}"
   data-label="Dashboard">
    <i class="fas fa-th-large"></i>
    <span>Dashboard</span>
</a>

<div class="nav-section-label">Work</div>

<a href="#" class="nav-item" data-label="Tickets">
    <i class="fas fa-ticket"></i>
    <span>Tickets</span>
</a>

<a href="#" class="nav-item" data-label="Clients">
    <i class="fas fa-users"></i>
    <span>Clients</span>
</a>

<a href="#" class="nav-item" data-label="Messages">
    <i class="fas fa-comment-dots"></i>
    <span>Messages</span>
</a>

<div class="nav-section-label">Account</div>

<a href=""
   class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}"
   data-label="Settings">
    <i class="fas fa-gear"></i>
    <span>Settings</span>
</a>
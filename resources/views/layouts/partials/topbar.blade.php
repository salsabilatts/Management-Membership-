<header class="topbar">
    <div class="topbar-left">
        <button class="menu-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <div>
            <h3>Selamat Datang, {{ Auth::user()->name }}</h3>
        </div>
    </div>
    <div class="topbar-right">
     <div class="notification-icon" onclick="toggleNotifications()">
    <i class="fas fa-bell"></i>
</div>
        
        
        <div class="user-info" onclick="toggleUserMenu()">
            <div class="user-avatar">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <div>
            <div style="font-weight: 600;">{{ Auth::user()->name }}</div>
<div style="font-size: 0.75rem; color: var(--secondary);">
    Admin
</div>
            <i class="fas fa-chevron-down" style="margin-left: 0.5rem;"></i>
        </div>

        <!-- User Dropdown Menu -->
        <div class="user-dropdown" id="userDropdown" style="display: none;">
         <a href="#" class="dropdown-item">
                <i class="fas fa-user"></i> Profile
        </a>
        <a href="#" class="dropdown-item">
                <i class="fas fa-cog"></i> Settings
        </a>
            <hr style="margin: 0.5rem 0;">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="dropdown-item" style="width: 100%; text-align: left;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>
</header>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h2><i class="fas fa-users"></i> Membership</h2>
        <p>Management System</p>
    </div>
    
    <nav class="menu">
        <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        
        <!-- Manajemen User -->
        <div class="menu-item" onclick="toggleSubmenu('userMenu')">
            <i class="fas fa-users"></i>
            <span>Manajemen User</span>
            <i class="fas fa-chevron-down" style="margin-left: auto;"></i>
        </div>
        <div class="submenu {{ request()->routeIs('admin.members.*') ? 'active' : '' }}" id="userMenu">
            <a href="{{ route('admin.members.index') }}" class="submenu-item">Data Anggota</a>
            <a href="{{ route('admin.members.create') }}" class="submenu-item">Tambah User</a>
        </div>

        <!-- Modul UMKM -->
        <div class="menu-item" onclick="toggleSubmenu('umkmMenu')">
            <i class="fas fa-store"></i>
            <span>Modul UMKM</span>
            <i class="fas fa-chevron-down" style="margin-left: auto;"></i>
        </div>
        <div class="submenu {{ request()->routeIs('admin.umkm.*') ? 'active' : '' }}" id="umkmMenu">
            <a href="{{ route('admin.umkm.index') }}" class="submenu-item">Data Pelaku Usaha</a>
            <a href="{{ route('admin.umkm.index', ['status' => 'pending']) }}" class="submenu-item">Verifikasi Usaha</a>
            <a href="{{ route('admin.umkm.export') }}" class="submenu-item">Laporan</a>
        </div>

        <!-- Modul Pendidikan -->
        <div class="menu-item" onclick="toggleSubmenu('pendidikanMenu')">
            <i class="fas fa-graduation-cap"></i>
            <span>Modul Pendidikan</span>
            <i class="fas fa-chevron-down" style="margin-left: auto;"></i>
        </div>
        <div class="submenu {{ request()->routeIs('admin.education-aids.*') ? 'active' : '' }}" id="pendidikanMenu">
            <a href="{{ route('admin.education-aids.index') }}" class="submenu-item">Data Penerima</a>
            <a href="{{ route('admin.education-aids.index', ['status' => 'pending']) }}" class="submenu-item">Verifikasi Pengajuan</a>
        </div>

        <!-- Modul Kesehatan -->
        <div class="menu-item" onclick="toggleSubmenu('kesehatanMenu')">
            <i class="fas fa-heartbeat"></i>
            <span>Modul Kesehatan</span>
            <i class="fas fa-chevron-down" style="margin-left: auto;"></i>
        </div>
        <div class="submenu {{ request()->routeIs('admin.health-events.*') ? 'active' : '' }}" id="kesehatanMenu">
            <a href="{{ route('admin.health-events.index') }}" class="submenu-item">List Event</a>
            <a href="{{ route('admin.health-events.create') }}" class="submenu-item">Buat Event</a>
        </div>

        <!-- Bantuan Hukum -->
        <div class="menu-item" onclick="toggleSubmenu('hukumMenu')">
            <i class="fas fa-balance-scale"></i>
            <span>Bantuan Hukum</span>
            <i class="fas fa-chevron-down" style="margin-left: auto;"></i>
        </div>
        <div class="submenu {{ request()->routeIs('admin.legal-aids.*') ? 'active' : '' }}" id="hukumMenu">
            <a href="{{ route('admin.legal-aids.index') }}" class="submenu-item">Data Pemohon</a>
            <a href="{{ route('admin.legal-aids.index', ['status' => 'pending']) }}" class="submenu-item">Verifikasi</a>
        </div>

        <!-- Modul Sosial -->
        <div class="menu-item" onclick="toggleSubmenu('sosialMenu')">
            <i class="fas fa-hands-helping"></i>
            <span>Modul Sosial</span>
            <i class="fas fa-chevron-down" style="margin-left: auto;"></i>
        </div>
        <div class="submenu {{ request()->routeIs('admin.social-activities.*') ? 'active' : '' }}" id="sosialMenu">
            <a href="{{ route('admin.social-activities.index') }}" class="submenu-item">Data Kegiatan</a>
            <a href="{{ route('admin.social-activities.create') }}" class="submenu-item">Buat Kegiatan</a>
        </div>

        <!-- E-Money -->
        <a href="{{ route('admin.emoney.index') }}" class="menu-item {{ request()->routeIs('admin.emoney.*') ? 'active' : '' }}">
            <i class="fas fa-credit-card"></i>
            <span>E-Money Management</span>
        </a>

        <!-- Laporan -->
        <div class="menu-item" onclick="toggleSubmenu('laporanMenu')">
            <i class="fas fa-chart-bar"></i>
            <span>Transaksi & Laporan</span>
            <i class="fas fa-chevron-down" style="margin-left: auto;"></i>
        </div>
        <div class="submenu {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" id="laporanMenu">
            <a href="{{ route('admin.reports.daily') }}" class="submenu-item">Laporan Harian</a>
            <a href="{{ route('admin.reports.monthly') }}" class="submenu-item">Laporan Bulanan</a>
            <a href="{{ route('admin.reports.export') }}" class="submenu-item">Export Data</a>
        </div>

        <!-- Lembaga -->
        <a href="{{ route('admin.institutions.index') }}" class="menu-item {{ request()->routeIs('admin.institutions.*') ? 'active' : '' }}">
            <i class="fas fa-building"></i>
            <span>Lembaga Mitra</span>
        </a>

    </nav>
</aside>

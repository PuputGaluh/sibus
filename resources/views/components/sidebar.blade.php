<div class="sidebar">
    <div class="logo-section">
        <div class="logo">🚌 INKA</div>
        <div class="title">Sistem Informasi Manajemen Kerusakan Bus Listrik</div>
    </div>

    <nav class="menu">
        {{-- DASHBOARD (SEMUA ROLE) --}}
        <a href="{{ route('dashboard') }}"
           class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>

        {{-- ADMIN --}}
        @if(auth()->user()->role === 'Admin')
            <a href="{{ route('admin.user') }}"
               class="menu-item {{ request()->routeIs('admin.user*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Kelola User</span>
            </a>

            <a href="{{ route('admin.bus') }}"
               class="menu-item {{ request()->routeIs('admin.bus*') ? 'active' : '' }}">
                <i class="fas fa-bus"></i>
                <span>Kelola Bus</span>
            </a>
        @endif

        {{-- TEKNISI --}}
        @if(auth()->user()->role === 'Teknisi')
            <a href="{{ route('laporan_kerusakan.index') }}"
               class="menu-item {{ request()->routeIs('laporan_kerusakan.*') ? 'active' : '' }}">
                <i class="fas fa-exclamation-triangle"></i>
                <span>Laporan Kerusakan</span>
            </a>

            <a href="{{ route('laporan_perbaikan.index') }}"
               class="menu-item {{ request()->routeIs('laporan_perbaikan.*') ? 'active' : '' }}">
                <i class="fas fa-tools"></i>
                <span>Laporan Perbaikan</span>
            </a>
        @endif

        {{-- DISPATCHER --}}
        @if(auth()->user()->role === 'Dispatcher')
            <a href="{{ route('laporan_kerusakan.index') }}"
               class="menu-item {{ request()->routeIs('laporan_kerusakan.*') ? 'active' : '' }}">
                <i class="fas fa-exclamation-triangle"></i>
                <span>Laporan Kerusakan</span>
            </a>

            <a href="{{ route('laporan_perbaikan.index') }}"
               class="menu-item {{ request()->routeIs('laporan_perbaikan.*') ? 'active' : '' }}">
                <i class="fas fa-tools"></i>
                <span>Laporan Perbaikan</span>
            </a>
        @endif

        {{-- MANAJER --}}
        @if(auth()->user()->role === 'Manajer')
            <a href="{{ route('rekap.index') }}"
               class="menu-item {{ request()->routeIs('rekap.*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span>Rekap Laporan</span>
            </a>
        @endif
    </nav>

    {{-- USER INFO --}}
    <div class="user-info">
        <span class="username">{{ auth()->user()->name }}</span>
        <span class="role">{{ auth()->user()->role }}</span>
    </div>

    {{-- LOGOUT --}}
    <form method="POST" action="{{ route('logout') }}" class="logout-form">
        @csrf
        <button type="submit" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </button>
    </form>
</div>

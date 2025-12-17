<div class="sidebar">
    <!-- Logo Section with Image -->
    <div class>
        <div class="logo-container">
            <img src="{{ asset('img/INKALogo.png') }}" alt="INKA Logo" class="logo-img">
        </div>
        <div class="brand-info">
            <!-- <h1 class="brand-title">Sistem Manajemen Bus Listrik</h1> -->
            <p class="brand-subtitle">Sistem Manajemen Bus Listrik</p>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="menu">
        {{-- DASHBOARD (SEMUA ROLE) --}}
        <a href="{{ route('dashboard') }}"
           class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <div class="menu-icon">
                <i class="fas fa-home"></i>
            </div>
            <span class="menu-text">Dashboard</span>
            <div class="menu-indicator"></div>
        </a>

        {{-- ADMIN --}}
        @if(auth()->user()->role === 'Admin')
            <div class="menu-divider">
                <span>Administrator</span>
            </div>

            <a href="{{ route('admin.user') }}"
               class="menu-item {{ request()->routeIs('admin.user*') ? 'active' : '' }}">
                <div class="menu-icon">
                    <i class="fas fa-users"></i>
                </div>
                <span class="menu-text">Kelola User</span>
                <div class="menu-indicator"></div>
            </a>

            <a href="{{ route('admin.bus') }}"
               class="menu-item {{ request()->routeIs('admin.bus*') ? 'active' : '' }}">
                <div class="menu-icon">
                    <i class="fas fa-bus"></i>
                </div>
                <span class="menu-text">Kelola Bus</span>
                <div class="menu-indicator"></div>
            </a>
        @endif

        {{-- TEKNISI --}}
        @if(auth()->user()->role === 'Teknisi')
            <div class="menu-divider">
                <span>Teknisi</span>
            </div>

            <a href="{{ route('laporan_kerusakan.index') }}"
               class="menu-item {{ request()->routeIs('laporan_kerusakan.*') ? 'active' : '' }}">
                <div class="menu-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <span class="menu-text">Laporan Kerusakan</span>
                <div class="menu-indicator"></div>
            </a>

            <a href="{{ route('laporan_perbaikan.index') }}"
               class="menu-item {{ request()->routeIs('laporan_perbaikan.*') ? 'active' : '' }}">
                <div class="menu-icon">
                    <i class="fas fa-tools"></i>
                </div>
                <span class="menu-text">Laporan Perbaikan</span>
                <div class="menu-indicator"></div>
            </a>
        @endif

        {{-- DISPATCHER --}}
        @if(auth()->user()->role === 'Dispatcher')
            <div class="menu-divider">
                <span>Dispatcher</span>
            </div>

            <a href="{{ route('laporan_kerusakan.index') }}"
               class="menu-item {{ request()->routeIs('laporan_kerusakan.*') ? 'active' : '' }}">
                <div class="menu-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <span class="menu-text">Laporan Kerusakan</span>
                <div class="menu-indicator"></div>
            </a>

            <a href="{{ route('laporan_perbaikan.index') }}"
               class="menu-item {{ request()->routeIs('laporan_perbaikan.*') ? 'active' : '' }}">
                <div class="menu-icon">
                    <i class="fas fa-tools"></i>
                </div>
                <span class="menu-text">Laporan Perbaikan</span>
                <div class="menu-indicator"></div>
            </a>
        @endif

        {{-- MANAJER --}}
        @if(auth()->user()->role === 'Manajer')
            <div class="menu-divider">
                <span>Manajer</span>
            </div>

            <a href="{{ route('rekap.index') }}"
               class="menu-item {{ request()->routeIs('rekap.*') ? 'active' : '' }}">
                <div class="menu-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <span class="menu-text">Rekap Laporan</span>
                <div class="menu-indicator"></div>
            </a>
        @endif
    </nav>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        {{-- USER INFO --}}
        <div class="user-info">
            <div class="user-details">
                <span class="username">{{ auth()->user()->name }}</span>
                <span class="role">{{ auth()->user()->role }}</span>
            </div>
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
</div>

<style>
/* ============================================
   SIDEBAR STYLING
   ============================================ */
.sidebar {
    width: 280px;
    height: 100vh;
    background: linear-gradient(180deg, #1a1f3a 0%, #0f1419 100%);
    position: fixed;
    left: 0;
    top: 0;
    display: flex;
    flex-direction: column;
    box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
    z-index: 1000;
    overflow: hidden;
}

/* ============================================
   LOGO SECTION
   ============================================ */
.logo-section {
    padding: 30px 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-bottom: 3px solid rgba(255, 255, 255, 0.1);
    animation: slideDown 0.5s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.logo-container {
    width: 80px;
    height: 80px;
    margin: 0 auto 15px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease;
}

.logo-container:hover {
    transform: scale(1.05) rotate(5deg);
}

.logo-img {
    width: 70px;
    height: 70px;
    object-fit: contain;
    border-radius: 50%;
}

.brand-info {
    text-align: center;
}

.brand-title {
    font-size: 24px;
    font-weight: 700;
    color: white;
    margin: 0 0 5px 0;
    letter-spacing: 2px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.brand-subtitle {
    font-size: 16px;
    color: rgba(255, 255, 255, 0.9);
    margin: 0;
    line-height: 1.4;
    font-weight: 500;
}

/* ============================================
   NAVIGATION MENU
   ============================================ */
.menu {
    flex: 1;
    padding: 20px 0;
    overflow-y: auto;
    overflow-x: hidden;
}

.menu::-webkit-scrollbar {
    width: 6px;
}

.menu::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
}

.menu::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 3px;
}

.menu::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
}

/* Menu Divider */
.menu-divider {
    padding: 20px 20px 10px 20px;
    margin-top: 10px;
}

.menu-divider span {
    font-size: 11px;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.5);
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Menu Item */
.menu-item {
    display: flex;
    align-items: center;
    padding: 14px 20px;
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    margin: 4px 10px;
    border-radius: 12px;
    overflow: hidden;
}

.menu-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1));
    transform: translateX(-100%);
    transition: transform 0.3s ease;
}

.menu-item:hover::before {
    transform: translateX(0);
}

.menu-item:hover {
    color: white;
    background: rgba(255, 255, 255, 0.1);
    transform: translateX(5px);
}

.menu-item.active {
    background: linear-gradient(135deg, #ff0303ff 0%, #f47979ff 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(255, 255, 255, 0.4);
}

.menu-item.active::before {
    display: none;
}

.menu-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    margin-right: 15px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    transition: all 0.3s ease;
}

.menu-item:hover .menu-icon {
    background: rgba(255, 255, 255, 0.2);
    transform: rotate(10deg) scale(1.1);
}

.menu-item.active .menu-icon {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.1);
}

.menu-text {
    font-size: 14px;
    font-weight: 500;
    flex: 1;
}

.menu-indicator {
    width: 6px;
    height: 6px;
    background: white;
    border-radius: 50%;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.menu-item.active .menu-indicator {
    opacity: 1;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.5;
        transform: scale(1.2);
    }
}

/* ============================================
   SIDEBAR FOOTER
   ============================================ */
.sidebar-footer {
    padding: 20px;
    background: rgba(0, 0, 0, 0.2);
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

/* User Info */
.user-info {
    display: flex;
    align-items: center;
    padding: 15px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 12px;
    margin-bottom: 15px;
    transition: all 0.3s ease;
}

.user-info:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-2px);
}

.user-avatar {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    font-size: 20px;
    color: white;
    box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
}

.user-details {
    display: flex;
    flex-direction: column;
    flex: 1;
}

.username {
    font-size: 14px;
    font-weight: 600;
    color: white;
    margin-bottom: 2px;
}

.role {
    font-size: 15px;
    color: rgba(255, 255, 255, 0.6);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Logout Button */
.logout-form {
    margin: 0;
}

.logout-btn {
    width: 100%;
    padding: 12px 15px;
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    border-radius: 10px;
    color: #ef4444;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.logout-btn:hover {
    background: #ef4444;
    color: white;
    border-color: #ef4444;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
}

.logout-btn i {
    font-size: 16px;
}

/* ============================================
   RESPONSIVE
   ============================================ */
@media (max-width: 768px) {
    .sidebar {
        width: 240px;
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }

    .sidebar.active {
        transform: translateX(0);
    }

    .logo-container {
        width: 60px;
        height: 60px;
    }

    .logo-img {
        width: 50px;
        height: 50px;
    }

    .brand-title {
        font-size: 20px;
    }

    .brand-subtitle {
        font-size: 10px;
    }

    .menu-item {
        padding: 12px 15px;
    }

    .menu-icon {
        width: 35px;
        height: 35px;
        font-size: 16px;
    }

    .menu-text {
        font-size: 13px;
    }
}

/* ============================================
   ANIMATIONS
   ============================================ */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.menu-item {
    animation: fadeIn 0.5s ease-out backwards;
}

.menu-item:nth-child(1) { animation-delay: 0.1s; }
.menu-item:nth-child(2) { animation-delay: 0.2s; }
.menu-item:nth-child(3) { animation-delay: 0.3s; }
.menu-item:nth-child(4) { animation-delay: 0.4s; }
.menu-item:nth-child(5) { animation-delay: 0.5s; }
.menu-item:nth-child(6) { animation-delay: 0.6s; }
</style>
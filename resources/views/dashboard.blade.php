@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-container">
    <!-- Header Section -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <div class="icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="3" y1="9" x2="21" y2="9"></line>
                        <line x1="9" y1="21" x2="9" y2="9"></line>
                    </svg>
                </div>
                <div>
                    <h1>Dashboard {{ $user->role }}</h1>
                    <p class="subtitle">Selamat datang kembali, <strong>{{ $user->name }}</strong></p>
                </div>
            </div>
            <div class="header-date">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <span>{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}</span>
            </div>
        </div>
    </div>

    {{-- ADMIN DASHBOARD --}}
    @if($user->role == 'Admin')
        <!-- Stats Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>{{ $totalUsers }}</h3>
                    <p>Total User</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M8 6v6M16 6v6M3 16h18M3 10h18M5 20h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z"></path>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>{{ $totalBus }}</h3>
                    <p>Total Bus</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon warning">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                        <line x1="12" y1="9" x2="12" y2="13"></line>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>{{ $totalLaporanKerusakan }}</h3>
                    <p>Laporan Kerusakan</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon info">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>{{ $totalLaporanPerbaikan }}</h3>
                    <p>Laporan Perbaikan</p>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-grid">
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Distribusi User</h3>
                </div>
                <div class="chart-body">
                    <canvas id="userDistributionChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <h3>Status Laporan</h3>
                </div>
                <div class="chart-body">
                    <canvas id="reportStatusChart"></canvas>
                </div>
            </div>
        </div>
    @endif

    {{-- DISPATCHER DASHBOARD --}}
    @if($user->role == 'Dispatcher')
        <!-- Stats Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon warning">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>{{ $laporanBelumDiproses }}</h3>
                    <p>Belum Diproses</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon info">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>{{ $laporanSedangDiproses }}</h3>
                    <p>Sedang Diproses</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>{{ $laporanSelesai }}</h3>
                    <p>Selesai</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 11l3 3L22 4"></path>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>{{ $myValidatedRepairs }}</h3>
                    <p>Saya Validasi</p>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-grid">
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Status Proses Laporan</h3>
                </div>
                <div class="chart-body">
                    <canvas id="statusProsesChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <h3>Tingkat Kerusakan</h3>
                </div>
                <div class="chart-body">
                    <canvas id="tingkatKerusakanChart"></canvas>
                </div>
            </div>
        </div>
    @endif

    {{-- TEKNISI DASHBOARD --}}
    @if($user->role == 'Teknisi')
        <!-- Stats Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 20h9"></path>
                        <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>{{ $totalMyRepairs }}</h3>
                    <p>Total Perbaikan</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon warning">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>{{ $myOngoingRepairs }}</h3>
                    <p>Sedang Diperbaiki</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>{{ $myCompletedRepairs }}</h3>
                    <p>Selesai</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                        <line x1="12" y1="9" x2="12" y2="13"></line>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>{{ $highPriorityCount }}</h3>
                    <p>Prioritas Tinggi</p>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-grid">
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Status Perbaikan</h3>
                </div>
                <div class="chart-body">
                    <canvas id="statusPerbaikanChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <h3>Prioritas Pekerjaan</h3>
                </div>
                <div class="chart-body">
                    <canvas id="prioritasChart"></canvas>
                </div>
            </div>
        </div>
    @endif

    {{-- MANAJER DASHBOARD --}}
    @if($user->role == 'Manajer')
        <!-- Stats Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>{{ $totalDamageReports }}</h3>
                    <p>Total Laporan</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon warning">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M12 1v6m0 6v6"></path>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>{{ $ongoingRepairs }}</h3>
                    <p>Sedang Diperbaiki</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>{{ $completedRepairs }}</h3>
                    <p>Selesai</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon info">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3>{{ round($avgRepairTime ?? 0, 1) }}<small>hari</small></h3>
                    <p>Rata-rata Waktu</p>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-grid">
            <div class="chart-card full-width">
                <div class="chart-header">
                    <h3>Trend 6 Bulan Terakhir</h3>
                </div>
                <div class="chart-body">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <h3>Kerusakan by Kategori</h3>
                </div>
                <div class="chart-body">
                    <canvas id="kategoriChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <h3>Kerusakan by Tingkat</h3>
                </div>
                <div class="chart-body">
                    <canvas id="tingkatChart"></canvas>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    :root {
        --primary: #dc2626;
        --primary-dark: #b91c1c;
        --secondary: #64748b;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --info: #3b82f6;
        --light: #f8fafc;
        --dark: #0f172a;
        --border: #fee2e2;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: #e5e7eb;
        min-height: 100vh;
    }

    .page-container {
        max-width: 100%;
        margin: 0;
        padding: 1rem;
        animation: fadeIn 0.5s ease;
        min-height: 100vh;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Header */
    .page-header {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.25rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        animation: slideDown 0.5s ease;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .icon-wrapper {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }

    .page-header h1 {
        font-size: 1.75rem;
        color: var(--dark);
        margin-bottom: 0.25rem;
    }

    .subtitle {
        color: var(--secondary);
        font-size: 0.95rem;
    }

    .header-date {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        background: var(--light);
        border-radius: 10px;
        color: var(--secondary);
        font-size: 0.9rem;
        font-weight: 500;
    }

    /* Stats Cards */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
        margin-bottom: 1.25rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        animation: scaleIn 0.5s ease;
    }

    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
    }

    .stat-icon.primary { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); }
    .stat-icon.success { background: linear-gradient(135deg, #10b981, #059669); }
    .stat-icon.warning { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .stat-icon.info { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
    .stat-icon.danger { background: linear-gradient(135deg, #ef4444, #dc2626); }

    .stat-content h3 {
        font-size: 2.25rem;
        color: var(--dark);
        margin-bottom: 0.25rem;
        font-weight: 700;
    }

    .stat-content h3 small {
        font-size: 0.875rem;
        color: var(--secondary);
        font-weight: 500;
        margin-left: 0.25rem;
    }

    .stat-content p {
        color: var(--secondary);
        font-size: 0.9rem;
        font-weight: 500;
    }

    /* Charts Grid */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
        margin-bottom: 1.25rem;
    }

    .chart-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        animation: scaleIn 0.5s ease;
    }

    .chart-card.full-width {
        grid-column: 1 / -1;
    }

    .chart-header {
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, #dc2626, #991b1b);
        color: white;
        border-bottom: 3px solid rgba(255, 255, 255, 0.1);
    }

    .chart-header h3 {
        font-size: 1.125rem;
        font-weight: 600;
        margin: 0;
    }

    .chart-body {
        padding: 2rem 1.5rem;
        min-height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .chart-body canvas {
        max-height: 300px;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .stats-container {
            grid-template-columns: repeat(2, 1fr);
        }

        .charts-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .page-container {
            padding: 1rem;
        }

        .page-header h1 {
            font-size: 1.5rem;
        }

        .header-content {
            flex-direction: column;
            align-items: stretch;
        }

        .stats-container {
            grid-template-columns: 1fr;
        }

        .stat-content h3 {
            font-size: 1.75rem;
        }

        .chart-body {
            padding: 1.5rem 1rem;
            min-height: 250px;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Common chart configuration
    const chartColors = {
        primary: '#dc2626',
        success: '#10b981',
        warning: '#f59e0b',
        info: '#3b82f6',
        danger: '#ef4444',
        secondary: '#64748b'
    };

    const gradientOptions = (ctx, color1, color2) => {
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, color1);
        gradient.addColorStop(1, color2);
        return gradient;
    };

    const defaultOptions = {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    font: { size: 12, weight: '500' },
                    usePointStyle: true,
                    pointStyle: 'circle'
                }
            }
        }
    };

    @if($user->role == 'Admin')
        // User Distribution Pie Chart
        const userDistCtx = document.getElementById('userDistributionChart');
        if (userDistCtx) {
            new Chart(userDistCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Admin', 'Dispatcher', 'Teknisi', 'Manajer'],
                    datasets: [{
                        data: [
                            {{ $adminCount }},
                            {{ $dispatcherCount }},
                            {{ $teknisiCount }},
                            {{ $manajerCount }}
                        ],
                        backgroundColor: [
                            chartColors.primary,
                            chartColors.success,
                            chartColors.warning,
                            chartColors.info
                        ],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    ...defaultOptions,
                    cutout: '65%',
                }
            });
        }

        // Report Status Bar Chart
        const reportStatusCtx = document.getElementById('reportStatusChart');
        if (reportStatusCtx) {
            new Chart(reportStatusCtx, {
                type: 'bar',
                data: {
                    labels: ['Kerusakan', 'Perbaikan'],
                    datasets: [{
                        label: 'Total',
                        data: [{{ $totalLaporanKerusakan }}, {{ $totalLaporanPerbaikan }}],
                        backgroundColor: [
                            gradientOptions(reportStatusCtx.getContext('2d'), chartColors.warning, '#d97706'),
                            gradientOptions(reportStatusCtx.getContext('2d'), chartColors.info, '#1d4ed8')
                        ],
                        borderRadius: 8,
                        borderSkipped: false
                    }]
                },
                options: {
                    ...defaultOptions,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { display: true, color: 'rgba(0,0,0,0.05)' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        }
    @endif

    @if($user->role == 'Dispatcher')
        // Status Proses Pie Chart
        const statusProsesCtx = document.getElementById('statusProsesChart');
        if (statusProsesCtx) {
            new Chart(statusProsesCtx, {
                type: 'pie',
                data: {
                    labels: ['Belum Diproses', 'Sedang Diproses', 'Selesai'],
                    datasets: [{
                        data: [
                            {{ $laporanBelumDiproses }},
                            {{ $laporanSedangDiproses }},
                            {{ $laporanSelesai }}
                        ],
                        backgroundColor: [
                            chartColors.warning,
                            chartColors.info,
                            chartColors.success
                        ],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: defaultOptions
            });
        }

        // Tingkat Kerusakan Doughnut Chart
        const tingkatCtx = document.getElementById('tingkatKerusakanChart');
        if (tingkatCtx) {
            new Chart(tingkatCtx, {
                type: 'doughnut',
                data: {
                    labels: [
                        @foreach($laporanByTingkat as $item)
                            '{{ $item->tingkat->nama_tingkat }}',
                        @endforeach
                    ],
                    datasets: [{
                        data: [
                            @foreach($laporanByTingkat as $item)
                                {{ $item->total }},
                            @endforeach
                        ],
                        backgroundColor: [
                            chartColors.success,
                            chartColors.warning,
                            chartColors.danger
                        ],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    ...defaultOptions,
                    cutout: '60%'
                }
            });
        }
    @endif

    @if($user->role == 'Teknisi')
        // Status Perbaikan Pie Chart
        const statusPerbaikanCtx = document.getElementById('statusPerbaikanChart');
        if (statusPerbaikanCtx) {
            new Chart(statusPerbaikanCtx, {
                type: 'pie',
                data: {
                    labels: ['Menunggu', 'Sedang Diperbaiki', 'Selesai'],
                    datasets: [{
                        data: [
                            {{ $myPendingRepairs }},
                            {{ $myOngoingRepairs }},
                            {{ $myCompletedRepairs }}
                        ],
                        backgroundColor: [
                            chartColors.secondary,
                            chartColors.warning,
                            chartColors.success
                        ],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: defaultOptions
            });
        }

        // Prioritas Bar Chart
        const prioritasCtx = document.getElementById('prioritasChart');
        if (prioritasCtx) {
            new Chart(prioritasCtx, {
                type: 'bar',
                data: {
                    labels: ['Rendah', 'Sedang', 'Tinggi'],
                    datasets: [{
                        label: 'Jumlah',
                        data: [
                            {{ $lowPriorityCount }},
                            {{ $mediumPriorityCount }},
                            {{ $highPriorityCount }}
                        ],
                        backgroundColor: [
                            chartColors.info,
                            chartColors.warning,
                            chartColors.danger
                        ],
                        borderRadius: 8,
                        borderSkipped: false
                    }]
                },
                options: {
                    ...defaultOptions,
                    indexAxis: 'y',
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: { display: true, color: 'rgba(0,0,0,0.05)' }
                        },
                        y: {
                            grid: { display: false }
                        }
                    }
                }
            });
        }
    @endif

    @if($user->role == 'Manajer')
        // Trend Line Chart
        const trendCtx = document.getElementById('trendChart');
        if (trendCtx) {
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: [
                        @foreach($monthlyTrend as $trend)
                            '{{ $trend['month'] }}',
                        @endforeach
                    ],
                    datasets: [
                        {
                            label: 'Laporan Kerusakan',
                            data: [
                                @foreach($monthlyTrend as $trend)
                                    {{ $trend['damage_reports'] }},
                                @endforeach
                            ],
                            borderColor: chartColors.warning,
                            backgroundColor: chartColors.warning + '20',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 3,
                            pointRadius: 5,
                            pointHoverRadius: 7
                        },
                        {
                            label: 'Perbaikan Selesai',
                            data: [
                                @foreach($monthlyTrend as $trend)
                                    {{ $trend['completed_repairs'] }},
                                @endforeach
                            ],
                            borderColor: chartColors.success,
                            backgroundColor: chartColors.success + '20',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 3,
                            pointRadius: 5,
                            pointHoverRadius: 7
                        }
                    ]
                },
                options: {
                    ...defaultOptions,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { display: true, color: 'rgba(0,0,0,0.05)' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        // Kategori Doughnut Chart
        const kategoriCtx = document.getElementById('kategoriChart');
        if (kategoriCtx) {
            new Chart(kategoriCtx, {
                type: 'doughnut',
                data: {
                    labels: [
                        @foreach($damageByCategory as $item)
                            '{{ $item->kategori->nama_kategori }}',
                        @endforeach
                    ],
                    datasets: [{
                        data: [
                            @foreach($damageByCategory as $item)
                                {{ $item->total }},
                            @endforeach
                        ],
                        backgroundColor: [
                            chartColors.primary,
                            chartColors.success,
                            chartColors.warning,
                            chartColors.info,
                            chartColors.danger
                        ],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    ...defaultOptions,
                    cutout: '60%'
                }
            });
        }

        // Tingkat Polar Chart
        const tingkatMgrCtx = document.getElementById('tingkatChart');
        if (tingkatMgrCtx) {
            new Chart(tingkatMgrCtx, {
                type: 'polarArea',
                data: {
                    labels: [
                        @foreach($damageByCategory->take(5) as $item)
                            '{{ $item->kategori->nama_kategori }}',
                        @endforeach
                    ],
                    datasets: [{
                        data: [
                            @foreach($damageByCategory->take(5) as $item)
                                {{ $item->total }},
                            @endforeach
                        ],
                        backgroundColor: [
                            chartColors.primary + '80',
                            chartColors.success + '80',
                            chartColors.warning + '80',
                            chartColors.info + '80',
                            chartColors.danger + '80'
                        ],
                        borderWidth: 0
                    }]
                },
                options: defaultOptions
            });
        }
    @endif
});
</script>
@endsection
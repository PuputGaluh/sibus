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
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                    </svg>
                </div>
                <div>
                    <h1>Dashboard</h1>
                    <p class="subtitle">Halo, <strong>{{ $user->name }}</strong> ({{ $user->role }})</p>
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
        <div class="content-wrapper">
            <div class="charts-grid">
                <!-- User Management Overview -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h3>Kelola Pengguna - Distribusi Role</h3>
                    </div>
                    <div class="chart-body">
                        <canvas id="userDistributionChart"></canvas>
                    </div>
                </div>

                <!-- Bus Overview -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h3>Kelola Bus - Status Armada</h3>
                    </div>
                    <div class="chart-body">
                        <canvas id="busStatusChart"></canvas>
                    </div>
                </div>

                <!-- User Activity Summary -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h3>Aktivitas Pengguna (30 Hari)</h3>
                    </div>
                    <div class="chart-body">
                        <canvas id="userActivityChart"></canvas>
                    </div>
                </div>

                <!-- Bus Condition Summary -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h3>Kondisi Bus Berdasarkan Kerusakan</h3>
                    </div>
                    <div class="chart-body">
                        <canvas id="busConditionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- DISPATCHER DASHBOARD --}}
    @if($user->role == 'Dispatcher')
        <div class="content-wrapper">
            <div class="charts-grid">
                <!-- Tingkat Kerusakan -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h3>Laporan per Tingkat Kerusakan</h3>
                    </div>
                    <div class="chart-body">
                        <canvas id="tingkatChart"></canvas>
                    </div>
                </div>

                <!-- Urgency Stats -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h3>Urgensi Laporan Aktif</h3>
                    </div>
                    <div class="chart-body">
                        <canvas id="urgencyChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Ranking Card -->
            <div class="ranking-card">
                <div class="ranking-header">
                    <div class="ranking-title-group">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path>
                            <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path>
                            <path d="M4 22h16"></path>
                            <path d="M6 18h.01"></path>
                            <path d="M18 18h.01"></path>
                            <path d="M9 14h6v4H9z"></path>
                        </svg>
                        <div>
                            <h2>Peringkat Bus</h2>
                            <p class="ranking-subtitle">Urutan berdasarkan kondisi keandalan</p>
                        </div>
                    </div>
                    <div class="ranking-stats">
                        <div class="stat-box">
                            <div class="stat-icon success">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </div>
                            <div class="stat-content">
                                <span class="stat-label">Total Bus</span>
                                <span class="stat-value">{{ $allBuses->count() }}</span>
                            </div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-icon info">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="16" x2="12" y2="12"></line>
                                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                </svg>
                            </div>
                            <div class="stat-content">
                                <span class="stat-label">Baik</span>
                                <span class="stat-value">{{ $allBuses->where('total_kerusakan','<=',5)->count() }}</span>
                            </div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-icon danger">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg>
                            </div>
                            <div class="stat-content">
                                <span class="stat-label">Perlu Perhatian</span>
                                <span class="stat-value">{{ $allBuses->where('total_kerusakan','>',8)->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ranking-body">
                    @forelse($allBuses as $i => $bus)
                        @php
                            $rank = $i + 1;
                            $maxKerusakan = $allBuses->max('total_kerusakan') ?? 1;
                            if ($bus->total_kerusakan <= 2) {
                                $status = 'success'; $label = 'Sangat Baik';
                            } elseif ($bus->total_kerusakan <= 5) {
                                $status = 'info'; $label = 'Baik';
                            } elseif ($bus->total_kerusakan <= 8) {
                                $status = 'warning'; $label = 'Perlu Perhatian';
                            } else {
                                $status = 'danger'; $label = 'Kritis';
                            }
                        @endphp

                        <div class="rank-item {{ $status }}">
                            <div class="rank-number">
                                @if($rank === 1)
                                    <div class="rank-medal gold">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    </div>
                                @elseif($rank === 2)
                                    <div class="rank-medal silver">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    </div>
                                @elseif($rank === 3)
                                    <div class="rank-medal bronze">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    </div>
                                @else
                                    <div class="rank-number-badge">
                                        <span>#{{ $rank }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="rank-info">
                                <div class="rank-bus-header">
                                    <div class="rank-bus-name">{{ $bus->nama_bus }}</div>
                                    <span class="rank-badge {{ $status }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                        </svg>
                                        {{ $label }}
                                    </span>
                                </div>
                                
                                <div class="rank-meta">
                                    <span class="rank-count-badge">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M12 5v14M5 12h14"></path>
                                        </svg>
                                        <strong>{{ $bus->total_kerusakan }}</strong> kerusakan
                                    </span>
                                </div>

                                <div class="rank-progress-full">
                                    <div class="rank-progress-bar">
                                        <div class="rank-progress-fill {{ $status }}"
                                             style="width: {{ $maxKerusakan ? ($bus->total_kerusakan/$maxKerusakan*100) : 0 }}%">
                                        </div>
                                    </div>
                                    <span class="progress-percentage">{{ $maxKerusakan ? round(($bus->total_kerusakan/$maxKerusakan*100), 0) : 0 }}%</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                            <p>Belum ada data bus</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    {{-- TEKNISI DASHBOARD --}}
    @if($user->role == 'Teknisi')
        <div class="content-wrapper">
            <div class="charts-grid">
                <!-- Work Trend -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h3>Tren Pekerjaan (7 Hari)</h3>
                    </div>
                    <div class="chart-body">
                        <canvas id="workTrendChart"></canvas>
                    </div>
                </div>

                <!-- Active Priorities -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h3>Prioritas Aktif</h3>
                    </div>
                    <div class="chart-body">
                        <canvas id="activePrioritiesChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Ranking Card -->
            <div class="ranking-card">
                <div class="ranking-header">
                    <div class="ranking-title-group">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path>
                            <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path>
                            <path d="M4 22h16"></path>
                            <path d="M6 18h.01"></path>
                            <path d="M18 18h.01"></path>
                            <path d="M9 14h6v4H9z"></path>
                        </svg>
                        <div>
                            <h2>Peringkat Bus</h2>
                            <p class="ranking-subtitle">Urutan berdasarkan kondisi keandalan</p>
                        </div>
                    </div>
                    <div class="ranking-stats">
                        <div class="stat-box">
                            <div class="stat-icon success">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </div>
                            <div class="stat-content">
                                <span class="stat-label">Total Bus</span>
                                <span class="stat-value">{{ $allBuses->count() }}</span>
                            </div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-icon info">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="16" x2="12" y2="12"></line>
                                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                </svg>
                            </div>
                            <div class="stat-content">
                                <span class="stat-label">Baik</span>
                                <span class="stat-value">{{ $allBuses->where('total_kerusakan','<=',5)->count() }}</span>
                            </div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-icon danger">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg>
                            </div>
                            <div class="stat-content">
                                <span class="stat-label">Perlu Perhatian</span>
                                <span class="stat-value">{{ $allBuses->where('total_kerusakan','>',5)->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ranking-body">
                    @forelse($allBuses as $i => $bus)
                        @php
                            $rank = $i + 1;
                            $maxKerusakan = $allBuses->max('total_kerusakan') ?? 1;
                            if ($bus->total_kerusakan <= 2) {
                                $status = 'success'; $label = 'Sangat Baik';
                            } elseif ($bus->total_kerusakan <= 5) {
                                $status = 'info'; $label = 'Baik';
                            } elseif ($bus->total_kerusakan <= 8) {
                                $status = 'warning'; $label = 'Perlu Perhatian';
                            } else {
                                $status = 'danger'; $label = 'Kritis';
                            }
                        @endphp

                        <div class="rank-item {{ $status }}">
                            <div class="rank-number">
                                @if($rank === 1)
                                    <div class="rank-medal gold">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    </div>
                                @elseif($rank === 2)
                                    <div class="rank-medal silver">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    </div>
                                @elseif($rank === 3)
                                    <div class="rank-medal bronze">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    </div>
                                @else
                                    <div class="rank-number-badge">
                                        <span>#{{ $rank }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="rank-info">
                                <div class="rank-bus-header">
                                    <div class="rank-bus-name">{{ $bus->nama_bus }}</div>
                                    <span class="rank-badge {{ $status }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                        </svg>
                                        {{ $label }}
                                    </span>
                                </div>
                                
                                <div class="rank-meta">
                                    <span class="rank-count-badge">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M12 5v14M5 12h14"></path>
                                        </svg>
                                        <strong>{{ $bus->total_kerusakan }}</strong> kerusakan
                                    </span>
                                </div>

                                <div class="rank-progress-full">
                                    <div class="rank-progress-bar">
                                        <div class="rank-progress-fill {{ $status }}"
                                             style="width: {{ $maxKerusakan ? ($bus->total_kerusakan/$maxKerusakan*100) : 0 }}%">
                                        </div>
                                    </div>
                                    <span class="progress-percentage">{{ $maxKerusakan ? round(($bus->total_kerusakan/$maxKerusakan*100), 0) : 0 }}%</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                            <p>Belum ada data bus</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    {{-- MANAJER DASHBOARD --}}
    @if($user->role == 'Manajer')
        <div class="content-wrapper">
            <div class="charts-grid">
                <!-- Monthly Trend -->
                <div class="chart-card wide">
                    <div class="chart-header">
                        <h3>Tren 6 Bulan Terakhir</h3>
                    </div>
                    <div class="chart-body">
                        <canvas id="monthlyTrendChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Ranking Card -->
            <div class="ranking-card">
                <div class="ranking-header">
                    <div class="ranking-title-group">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path>
                            <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path>
                            <path d="M4 22h16"></path>
                            <path d="M6 18h.01"></path>
                            <path d="M18 18h.01"></path>
                            <path d="M9 14h6v4H9z"></path>
                        </svg>
                        <div>
                            <h2>Peringkat Bus</h2>
                            <p class="ranking-subtitle">Urutan berdasarkan kondisi keandalan</p>
                        </div>
                    </div>
                    <div class="ranking-stats">
                        <div class="stat-box">
                            <div class="stat-icon success">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </div>
                            <div class="stat-content">
                                <span class="stat-label">Total Bus</span>
                                <span class="stat-value">{{ $allBuses->count() }}</span>
                            </div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-icon info">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="16" x2="12" y2="12"></line>
                                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                </svg>
                            </div>
                            <div class="stat-content">
                                <span class="stat-label">Baik</span>
                                <span class="stat-value">{{ $allBuses->where('total_kerusakan','<=',5)->count() }}</span>
                            </div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-icon danger">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg>
                            </div>
                            <div class="stat-content">
                                <span class="stat-label">Perlu Perhatian</span>
                                <span class="stat-value">{{ $allBuses->where('total_kerusakan','>',5)->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ranking-body">
                    @forelse($allBuses as $i => $bus)
                        @php
                            $rank = $i + 1;
                            $maxKerusakan = $allBuses->max('total_kerusakan') ?? 1;
                            if ($bus->total_kerusakan <= 2) {
                                $status = 'success'; $label = 'Sangat Baik';
                            } elseif ($bus->total_kerusakan <= 5) {
                                $status = 'info'; $label = 'Baik';
                            } elseif ($bus->total_kerusakan <= 8) {
                                $status = 'warning'; $label = 'Perlu Perhatian';
                            } else {
                                $status = 'danger'; $label = 'Kritis';
                            }
                        @endphp

                        <div class="rank-item {{ $status }}">
                            <div class="rank-number">
                                @if($rank === 1)
                                    <div class="rank-medal gold">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    </div>
                                @elseif($rank === 2)
                                    <div class="rank-medal silver">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    </div>
                                @elseif($rank === 3)
                                    <div class="rank-medal bronze">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    </div>
                                @else
                                    <div class="rank-number-badge">
                                        <span>#{{ $rank }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="rank-info">
                                <div class="rank-bus-header">
                                    <div class="rank-bus-name">{{ $bus->nama_bus }}</div>
                                    <span class="rank-badge {{ $status }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                        </svg>
                                        {{ $label }}
                                    </span>
                                </div>
                                
                                <div class="rank-meta">
                                    <span class="rank-count-badge">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M12 5v14M5 12h14"></path>
                                        </svg>
                                        <strong>{{ $bus->total_kerusakan }}</strong> kerusakan
                                    </span>
                                </div>

                                <div class="rank-progress-full">
                                    <div class="rank-progress-bar">
                                        <div class="rank-progress-fill {{ $status }}"
                                             style="width: {{ $maxKerusakan ? ($bus->total_kerusakan/$maxKerusakan*100) : 0 }}%">
                                        </div>
                                    </div>
                                    <span class="progress-percentage">{{ $maxKerusakan ? round(($bus->total_kerusakan/$maxKerusakan*100), 0) : 0 }}%</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                            <p>Belum ada data bus</p>
                        </div>
                    @endforelse
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
        --border: #e2e8f0;
        --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07);
        --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: linear-gradient(135deg, #f5f7fa 0%, #e8eaf0 100%);
        height: 100vh;
        overflow: hidden;
        -webkit-font-smoothing: antialiased;
    }

    .page-container {
        max-width: 100%;
        height: 100vh;
        margin: 0;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }

    /* Header */
    .page-header {
        background: white;
        border-radius: 16px;
        padding: 1.5rem 2rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border);
        flex-shrink: 0;
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
        gap: 1.25rem;
    }

    .icon-wrapper {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        box-shadow: 0 8px 16px rgba(220, 38, 38, 0.25);
    }

    .page-header h1 {
        font-size: 1.875rem;
        color: var(--dark);
        margin-bottom: 0.25rem;
        font-weight: 700;
    }

    .subtitle {
        color: var(--secondary);
        font-size: 0.9375rem;
    }

    .header-date {
        display: flex;
        align-items: center;
        gap: 0.625rem;
        padding: 0.875rem 1.5rem;
        background: var(--light);
        border-radius: 12px;
        color: var(--secondary);
        font-size: 0.9375rem;
        font-weight: 500;
        border: 1px solid var(--border);
    }

    /* Content Wrapper */
    .content-wrapper {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        padding-right: 0.5rem;
    }

    .content-wrapper::-webkit-scrollbar {
        width: 6px;
    }

    .content-wrapper::-webkit-scrollbar-track {
        background: transparent;
    }

    .content-wrapper::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .content-wrapper::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Charts Grid */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 1400px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }
    }

    .chart-card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border);
        overflow: hidden;
        animation: scaleIn 0.5s ease;
    }

    .chart-card.wide {
        grid-column: 1 / -1;
    }

    .chart-header {
        padding: 1.5rem 1.75rem;
        background: linear-gradient(135deg, #dc2626, #991b1b);
        color: white;
    }

    .chart-header h3 {
        font-size: 1.125rem;
        font-weight: 600;
        margin: 0;
    }

    .chart-body {
        padding: 1.5rem 1.75rem;
        min-height: 280px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .chart-body canvas {
        max-height: 280px;
    }

    /* Performance Summary (Teknisi) */
    .performance-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1.5rem;
        width: 100%;
    }

    .perf-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 1rem;
    }

    .perf-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .perf-icon.success { background: linear-gradient(135deg, #10b981, #059669); }
    .perf-icon.warning { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .perf-icon.danger { background: linear-gradient(135deg, #ef4444, #dc2626); }

    .perf-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark);
    }

    .perf-label {
        font-size: 0.875rem;
        color: var(--secondary);
        font-weight: 500;
    }

    /* Performance Metrics (Manajer) */
    .performance-metrics {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1.5rem;
        width: 100%;
    }

    .metric-item {
        padding: 1rem;
        border-radius: 12px;
        background: var(--light);
        border: 1px solid var(--border);
        text-align: center;
    }

    .metric-label {
        font-size: 0.875rem;
        color: var(--secondary);
        margin-bottom: 0.75rem;
    }

    .metric-value-large {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.5rem;
    }

    .metric-value-large.danger {
        color: var(--danger);
    }

    .metric-progress {
        height: 8px;
        background: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
    }

    .metric-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #10b981, #059669);
        border-radius: 10px;
        transition: width 0.8s ease;
    }

    /* Ranking Card */
    .ranking-card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border);
        overflow: hidden;
        animation: scaleIn 0.6s ease;
        max-height: 650px;
        display: flex;
        flex-direction: column;
    }

    /* Ranking Header dengan Stats */
    .ranking-header {
        padding: 1.75rem 2rem;
        background: linear-gradient(135deg, #fafafa, #ffffff);
        border-bottom: 2px solid var(--border);
        flex-shrink: 0;
    }

    .ranking-title-group {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .ranking-title-group svg {
        color: var(--primary);
        flex-shrink: 0;
    }

    .ranking-title-group h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark);
        margin: 0 0 0.25rem 0;
    }

    .ranking-subtitle {
        font-size: 0.9375rem;
        color: var(--secondary);
        margin: 0;
    }

    /* Ranking Stats Box */
    .ranking-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .stat-box {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.875rem 1rem;
        background: var(--light);
        border-radius: 10px;
        border: 1px solid var(--border);
        transition: all 0.3s ease;
    }

    .stat-box:hover {
        border-color: var(--primary);
        background: white;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
    }

    .stat-icon.success {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
    }

    .stat-icon.info {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1e3a8a;
    }

    .stat-icon.danger {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
    }

    .stat-content {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .stat-label {
        font-size: 0.75rem;
        color: var(--secondary);
        font-weight: 500;
        text-transform: uppercase;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark);
    }

    /* Content Wrapper */
    .content-wrapper {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        padding-right: 0.5rem;
    }

    .content-wrapper::-webkit-scrollbar {
        width: 6px;
    }

    .content-wrapper::-webkit-scrollbar-track {
        background: transparent;
    }

    .content-wrapper::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .content-wrapper::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Charts Grid */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 1400px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }
    }

    .chart-card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border);
        overflow: hidden;
        animation: scaleIn 0.5s ease;
    }

    .chart-card.wide {
        grid-column: 1 / -1;
    }

    .chart-header {
        padding: 1.5rem 1.75rem;
        background: linear-gradient(135deg, #dc2626, #991b1b);
        color: white;
    }

    .chart-header h3 {
        font-size: 1.125rem;
        font-weight: 600;
        margin: 0;
    }

    .chart-body {
        padding: 1.5rem 1.75rem;
        min-height: 280px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .chart-body canvas {
        max-height: 280px;
    }

    /* Performance Summary (Teknisi) */
    .performance-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1.5rem;
        width: 100%;
    }

    .perf-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 1rem;
    }

    .perf-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .perf-icon.success { background: linear-gradient(135deg, #10b981, #059669); }
    .perf-icon.warning { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .perf-icon.danger { background: linear-gradient(135deg, #ef4444, #dc2626); }

    .perf-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark);
    }

    .perf-label {
        font-size: 0.875rem;
        color: var(--secondary);
        font-weight: 500;
    }

    /* Performance Metrics (Manajer) */
    .performance-metrics {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1.5rem;
        width: 100%;
    }

    .metric-item {
        padding: 1rem;
        border-radius: 12px;
        background: var(--light);
        border: 1px solid var(--border);
        text-align: center;
    }

    .metric-label {
        font-size: 0.875rem;
        color: var(--secondary);
        margin-bottom: 0.75rem;
    }

    .metric-value-large {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.5rem;
    }

    .metric-value-large.danger {
        color: var(--danger);
    }

    .metric-progress {
        height: 8px;
        background: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
    }

    .metric-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #10b981, #059669);
        border-radius: 10px;
        transition: width 0.8s ease;
    }

    /* Ranking Card */
    .ranking-card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border);
        overflow: hidden;
        animation: scaleIn 0.6s ease;
        max-height: 650px;
        display: flex;
        flex-direction: column;
    }

    /* Ranking Header dengan Stats */
    .ranking-header {
        padding: 1.75rem 2rem;
        background: linear-gradient(135deg, #fafafa, #ffffff);
        border-bottom: 2px solid var(--border);
        flex-shrink: 0;
    }

    .ranking-title-group {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .ranking-title-group svg {
        color: var(--primary);
        flex-shrink: 0;
    }

    .ranking-title-group h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark);
        margin: 0 0 0.25rem 0;
    }

    .ranking-subtitle {
        font-size: 0.9375rem;
        color: var(--secondary);
        margin: 0;
    }

    /* Ranking Stats Box */
    .ranking-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .stat-box {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.875rem 1rem;
        background: var(--light);
        border-radius: 10px;
        border: 1px solid var(--border);
        transition: all 0.3s ease;
    }

    .stat-box:hover {
        border-color: var(--primary);
        background: white;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
    }

    .stat-icon.success {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
    }

    .stat-icon.info {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1e3a8a;
    }

    .stat-icon.danger {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
    }

    .stat-content {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .stat-label {
        font-size: 0.75rem;
        color: var(--secondary);
        font-weight: 500;
        text-transform: uppercase;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark);
    }

    /* Content Wrapper */
    .content-wrapper {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        padding-right: 0.5rem;
    }

    .content-wrapper::-webkit-scrollbar {
        width: 6px;
    }

    .content-wrapper::-webkit-scrollbar-track {
        background: transparent;
    }

    .content-wrapper::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .content-wrapper::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Charts Grid */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 1400px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }
    }

    .chart-card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border);
        overflow: hidden;
        animation: scaleIn 0.5s ease;
    }

    .chart-card.wide {
        grid-column: 1 / -1;
    }

    .chart-header {
        padding: 1.5rem 1.75rem;
        background: linear-gradient(135deg, #dc2626, #991b1b);
        color: white;
    }

    .chart-header h3 {
        font-size: 1.125rem;
        font-weight: 600;
        margin: 0;
    }

    .chart-body {
        padding: 1.5rem 1.75rem;
        min-height: 280px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .chart-body canvas {
        max-height: 280px;
    }

    /* Performance Summary (Teknisi) */
    .performance-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1.5rem;
        width: 100%;
    }

    .perf-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 1rem;
    }

    .perf-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .perf-icon.success { background: linear-gradient(135deg, #10b981, #059669); }
    .perf-icon.warning { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .perf-icon.danger { background: linear-gradient(135deg, #ef4444, #dc2626); }

    .perf-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark);
    }

    .perf-label {
        font-size: 0.875rem;
        color: var(--secondary);
        font-weight: 500;
    }

    /* Performance Metrics (Manajer) */
    .performance-metrics {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1.5rem;
        width: 100%;
    }

    .metric-item {
        padding: 1rem;
        border-radius: 12px;
        background: var(--light);
        border: 1px solid var(--border);
        text-align: center;
    }

    .metric-label {
        font-size: 0.875rem;
        color: var(--secondary);
        margin-bottom: 0.75rem;
    }

    .metric-value-large {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.5rem;
    }

    .metric-value-large.danger {
        color: var(--danger);
    }

    .metric-progress {
        height: 8px;
        background: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
    }

    .metric-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #10b981, #059669);
        border-radius: 10px;
        transition: width 0.8s ease;
    }

    /* Ranking Card */
    .ranking-card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border);
        overflow: hidden;
        animation: scaleIn 0.6s ease;
        max-height: 650px;
        display: flex;
        flex-direction: column;
    }

    /* Ranking Header dengan Stats */
    .ranking-header {
        padding: 1.75rem 2rem;
        background: linear-gradient(135deg, #fafafa, #ffffff);
        border-bottom: 2px solid var(--border);
        flex-shrink: 0;
    }

    .ranking-title-group {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .ranking-title-group svg {
        color: var(--primary);
        flex-shrink: 0;
    }

    .ranking-title-group h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark);
        margin: 0 0 0.25rem 0;
    }

    .ranking-subtitle {
        font-size: 0.9375rem;
        color: var(--secondary);
        margin: 0;
    }

    /* Ranking Stats Box */
    .ranking-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .stat-box {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.875rem 1rem;
        background: var(--light);
        border-radius: 10px;
        border: 1px solid var(--border);
        transition: all 0.3s ease;
    }

    .stat-box:hover {
        border-color: var(--primary);
        background: white;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
    }

    .stat-icon.success {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
    }

    .stat-icon.info {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1e3a8a;
    }

    .stat-icon.danger {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
    }

    .stat-content {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .stat-label {
        font-size: 0.75rem;
        color: var(--secondary);
        font-weight: 500;
        text-transform: uppercase;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark);
    }

    /* Content Wrapper */
    .content-wrapper {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        padding-right: 0.5rem;
    }

    .content-wrapper::-webkit-scrollbar {
        width: 6px;
    }

    .content-wrapper::-webkit-scrollbar-track {
        background: transparent;
    }

    .content-wrapper::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .content-wrapper::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Charts Grid */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 1400px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }
    }

    .chart-card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border);
        overflow: hidden;
        animation: scaleIn 0.5s ease;
    }

    .chart-card.wide {
        grid-column: 1 / -1;
    }

    .chart-header {
        padding: 1.5rem 1.75rem;
        background: linear-gradient(135deg, #dc2626, #991b1b);
        color: white;
    }

    .chart-header h3 {
        font-size: 1.125rem;
        font-weight: 600;
        margin: 0;
    }

    .chart-body {
        padding: 1.5rem 1.75rem;
        min-height: 280px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .chart-body canvas {
        max-height: 280px;
    }

    /* Performance Summary (Teknisi) */
    .performance-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1.5rem;
        width: 100%;
    }

    .perf-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 1rem;
    }

    .perf-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .perf-icon.success { background: linear-gradient(135deg, #10b981, #059669); }
    .perf-icon.warning { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .perf-icon.danger { background: linear-gradient(135deg, #ef4444, #dc2626); }

    .perf-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark);
    }

    .perf-label {
        font-size: 0.875rem;
        color: var(--secondary);
        font-weight: 500;
    }

    /* Performance Metrics (Manajer) */
    .performance-metrics {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1.5rem;
        width: 100%;
    }

    .metric-item {
        padding: 1rem;
        border-radius: 12px;
        background: var(--light);
        border: 1px solid var(--border);
        text-align: center;
    }

    .metric-label {
        font-size: 0.875rem;
        color: var(--secondary);
        margin-bottom: 0.75rem;
    }

    .metric-value-large {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.5rem;
    }

    .metric-value-large.danger {
        color: var(--danger);
    }

    .metric-progress {
        height: 8px;
        background: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
    }

    .metric-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #10b981, #059669);
        border-radius: 10px;
        transition: width 0.8s ease;
    }

    /* Ranking Card */
    .ranking-card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border);
        overflow: hidden;
        animation: scaleIn 0.6s ease;
        max-height: 650px;
        display: flex;
        flex-direction: column;
    }

    /* Ranking Header dengan Stats */
    .ranking-header {
        padding: 1.75rem 2rem;
        background: linear-gradient(135deg, #fafafa, #ffffff);
        border-bottom: 2px solid var(--border);
        flex-shrink: 0;
    }

    .ranking-title-group {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .ranking-title-group svg {
        color: var(--primary);
        flex-shrink: 0;
    }

    .ranking-title-group h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark);
        margin: 0 0 0.25rem 0;
    }

    .ranking-subtitle {
        font-size: 0.9375rem;
        color: var(--secondary);
        margin: 0;
    }

    /* Ranking Stats Box */
    .ranking-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .stat-box {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.875rem 1rem;
        background: var(--light);
        border-radius: 10px;
        border: 1px solid var(--border);
        transition: all 0.3s ease;
    }

    .stat-box:hover {
        border-color: var(--primary);
        background: white;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
    }

    .stat-icon.success {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
    }

    .stat-icon.info {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1e3a8a;
    }

    .stat-icon.danger {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
    }

    .stat-content {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .stat-label {
        font-size: 0.75rem;
        color: var(--secondary);
        font-weight: 500;
        text-transform: uppercase;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark);
    }

    /* Rank Item - Improved Layout */
    .rank-item {
        display: flex;
        align-items: stretch;
        gap: 1.25rem;
        padding: 1.25rem;
        margin-bottom: 1rem;
        background: white;
        border-radius: 12px;
        border: 2px solid var(--border);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .rank-item:hover {
        transform: translateX(6px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        border-color: var(--primary);
    }

    .rank-item:last-child {
        margin-bottom: 0;
    }

    /* Status Classes with enhanced colors */
    .rank-item.success {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.05), rgba(16, 185, 129, 0.02));
        border-color: #a7f3d0;
    }

    .rank-item.success:hover {
        border-color: #10b981;
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.15);
    }

    .rank-item.info {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), rgba(59, 130, 246, 0.02));
        border-color: #bfdbfe;
    }

    .rank-item.info:hover {
        border-color: #3b82f6;
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.15);
    }

    .rank-item.warning {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.05), rgba(245, 158, 11, 0.02));
        border-color: #fde68a;
    }

    .rank-item.warning:hover {
        border-color: #f59e0b;
        box-shadow: 0 8px 20px rgba(245, 158, 11, 0.15);
    }

    .rank-item.danger {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.05), rgba(239, 68, 68, 0.02));
        border-color: #fecaca;
    }

    .rank-item.danger:hover {
        border-color: #ef4444;
        box-shadow: 0 8px 20px rgba(239, 68, 68, 0.15);
    }

    /* Rank Info - Enhanced */
    .rank-info {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .rank-bus-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .rank-bus-name {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--dark);
        flex: 1;
    }

    .rank-meta {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .rank-badge {
        padding: 0.375rem 0.875rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        white-space: nowrap;
    }

    .rank-badge.success {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
    }

    .rank-badge.info {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1e3a8a;
    }

    .rank-badge.warning {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #92400e;
    }

    .rank-badge.danger {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
    }

    .rank-count-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.375rem 0.875rem;
        background: var(--light);
        border-radius: 8px;
        font-size: 0.875rem;
        color: var(--secondary);
        font-weight: 500;
        border: 1px solid var(--border);
    }

    .rank-count-badge strong {
        color: var(--dark);
        font-weight: 700;
    }

    /* Enhanced Progress Bar */
    .rank-progress-full {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .rank-progress-bar {
        flex: 1;
        height: 10px;
        background: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .rank-progress-fill {
        height: 100%;
        border-radius: 10px;
        transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .rank-progress-fill::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    .rank-progress-fill.success {
        background: linear-gradient(90deg, #10b981, #059669);
    }

    .rank-progress-fill.info {
        background: linear-gradient(90deg, #3b82f6, #1d4ed8);
    }

    .rank-progress-fill.warning {
        background: linear-gradient(90deg, #f59e0b, #d97706);
    }

    .rank-progress-fill.danger {
        background: linear-gradient(90deg, #ef4444, #dc2626);
    }

    .progress-percentage {
        min-width: 40px;
        font-size: 0.875rem;
        font-weight: 700;
        color: var(--secondary);
        text-align: right;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--secondary);
    }

    .empty-state svg {
        margin-bottom: 1.5rem;
        opacity: 0.25;
    }

    .empty-state p {

        font-size: 1.125rem;
        margin: 0;
        font-weight: 500;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-container {
            padding: 1rem;
        }

        .page-header {
            padding: 1.5rem;
        }

        .page-header h1 {
            font-size: 1.5rem;
        }

        .icon-wrapper {
            width: 56px;
            height: 56px;
        }

        .header-content {
            flex-direction: column;
            align-items: stretch;
        }

        .charts-grid {
            grid-template-columns: 1fr;
        }

        .ranking-card {
            max-height: 550px;
        }

        .ranking-header {
            padding: 1.5rem;
        }

        .ranking-title-group h2 {
            font-size: 1.25rem;
        }

        .ranking-body {
            padding: 1.25rem;
        }

        .rank-item {
            flex-wrap: wrap;
            padding: 1rem;
        }

        .rank-bus-header {
            width: 100%;
            flex-direction: column;
            align-items: flex-start;
        }

        .rank-progress-full {
            width: 100%;
        }

        .progress-percentage {
            min-width: auto;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartColors = {
        primary: '#dc2626',
        success: '#10b981',
        warning: '#f59e0b',
        info: '#3b82f6',
        danger: '#ef4444',
        secondary: '#64748b'
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
        // User Distribution Chart
        const userDistributionCtx = document.getElementById('userDistributionChart');
        if (userDistributionCtx) {
            new Chart(userDistributionCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Admin', 'Dispatcher', 'Teknisi', 'Manajer'],
                    datasets: [{
                        data: [
                            {{ $userActivity['admin'] ?? 0 }},
                            {{ $userActivity['dispatcher'] ?? 0 }},
                            {{ $userActivity['teknisi'] ?? 0 }},
                            {{ $userActivity['manajer'] ?? 0 }}
                        ],
                        backgroundColor: [chartColors.danger, chartColors.info, chartColors.success, chartColors.warning],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    ...defaultOptions,
                    cutout: '65%'
                }
            });
        }

        // Bus Status Chart
        const busStatusCtx = document.getElementById('busStatusChart');
        if (busStatusCtx) {
            new Chart(busStatusCtx, {
                type: 'bar',
                data: {
                    labels: ['Total Bus', 'Bus Aktif', 'Perlu Perhatian'],
                    datasets: [{
                        label: 'Jumlah',
                        data: [
                            {{ $totalBus }},
                            {{ $totalBus - ($busData['critical'] ?? 0) }},
                            {{ $busData['critical'] ?? 0 }}
                        ],
                        backgroundColor: [chartColors.primary, chartColors.success, chartColors.warning],
                        borderRadius: 8,
                        borderSkipped: false
                    }]
                },
                options: {
                    ...defaultOptions,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }

        // User Activity Chart
        const userActivityCtx = document.getElementById('userActivityChart');
        if (userActivityCtx) {
            new Chart(userActivityCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode(array_column($dailyActivity, 'date')) !!},
                    datasets: [
                        {
                            label: 'Dispatcher',
                            data: {!! json_encode(array_column($dailyActivity, 'dispatcher')) !!},
                            borderColor: chartColors.info,
                            backgroundColor: chartColors.info + '20',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Teknisi',
                            data: {!! json_encode(array_column($dailyActivity, 'teknisi')) !!},
                            borderColor: chartColors.success,
                            backgroundColor: chartColors.success + '20',
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    ...defaultOptions,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }

        // Bus Condition Chart
        const busConditionCtx = document.getElementById('busConditionChart');
        if (busConditionCtx) {
            new Chart(busConditionCtx, {
                type: 'bar',
                data: {
                    labels: ['Sempurna', 'Baik', 'Perlu Perhatian', 'Kritis'],
                    datasets: [{
                        label: 'Jumlah Bus',
                        data: [
                            {{ $busCondition['perfect'] ?? 0 }},
                            {{ $busCondition['good'] ?? 0 }},
                            {{ $busCondition['warning'] ?? 0 }},
                            {{ $busCondition['critical'] ?? 0 }}
                        ],
                        backgroundColor: [chartColors.success, chartColors.info, chartColors.warning, chartColors.danger],
                        borderRadius: 8,
                        borderSkipped: false
                    }]
                },
                options: {
                    ...defaultOptions,
                    indexAxis: 'y',
                    scales: {
                        x: { beginAtZero: true }
                    }
                }
            });
        }
    @endif

    @if($user->role == 'Dispatcher')
        // Tingkat Chart
        const tingkatCtx = document.getElementById('tingkatChart');
        if (tingkatCtx) {
            new Chart(tingkatCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($laporanByTingkat->pluck('tingkat.nama_tingkat')) !!},
                    datasets: [{
                        data: {!! json_encode($laporanByTingkat->pluck('total')) !!},
                        backgroundColor: [chartColors.success, chartColors.warning, chartColors.danger],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    ...defaultOptions,
                    cutout: '65%'
                }
            });
        }

        // Urgency Chart
        const urgencyCtx = document.getElementById('urgencyChart');
        if (urgencyCtx) {
            new Chart(urgencyCtx, {
                type: 'bar',
                data: {
                    labels: ['Ringan', 'Sedang', 'Berat'],
                    datasets: [{
                        label: 'Jumlah',
                        data: [
                            {{ $urgencyStats['low'] }},
                            {{ $urgencyStats['moderate'] }},
                            {{ $urgencyStats['critical'] }}
                        ],
                        backgroundColor: [chartColors.success, chartColors.warning, chartColors.danger],
                        borderRadius: 8,
                        borderSkipped: false
                    }]
                },
                options: {
                    ...defaultOptions,
                    indexAxis: 'y',
                    scales: {
                        x: { beginAtZero: true }
                    }
                }
            });
        }
    @endif

    @if($user->role == 'Teknisi')
        // Work Trend
        const workTrendCtx = document.getElementById('workTrendChart');
        if (workTrendCtx) {
            new Chart(workTrendCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode(array_column($workTrend, 'date')) !!},
                    datasets: [
                        {
                            label: 'Dimulai',
                            data: {!! json_encode(array_column($workTrend, 'started')) !!},
                            borderColor: chartColors.info,
                            backgroundColor: chartColors.info + '20',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Selesai',
                            data: {!! json_encode(array_column($workTrend, 'completed')) !!},
                            borderColor: chartColors.success,
                            backgroundColor: chartColors.success + '20',
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    ...defaultOptions,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }

        // Active Priorities
        const activePrioritiesCtx = document.getElementById('activePrioritiesChart');
        if (activePrioritiesCtx) {
            new Chart(activePrioritiesCtx, {
                type: 'bar',
                data: {
                    labels: ['Rendah', 'Sedang', 'Tinggi'],
                    datasets: [{
                        label: 'Jumlah',
                        data: [
                            {{ $activePriorities['rendah'] }},
                            {{ $activePriorities['sedang'] }},
                            {{ $activePriorities['tinggi'] }}
                        ],
                        backgroundColor: [chartColors.info, chartColors.warning, chartColors.danger],
                        borderRadius: 8,
                        borderSkipped: false
                    }]
                },
                options: {
                    ...defaultOptions,
                    indexAxis: 'y',
                    scales: {
                        x: { beginAtZero: true }
                    }
                }
            });
        }
    @endif

    @if($user->role == 'Manajer')
        // Monthly Trend
        const monthlyTrendCtx = document.getElementById('monthlyTrendChart');
        if (monthlyTrendCtx) {
            new Chart(monthlyTrendCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode(array_column($monthlyTrend, 'month')) !!},
                    datasets: [
                        {
                            label: 'Laporan Kerusakan',
                            data: {!! json_encode(array_column($monthlyTrend, 'damage_reports')) !!},
                            borderColor: chartColors.warning,
                            backgroundColor: chartColors.warning + '20',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Perbaikan Selesai',
                            data: {!! json_encode(array_column($monthlyTrend, 'completed_repairs')) !!},
                            borderColor: chartColors.success,
                            backgroundColor: chartColors.success + '20',
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    ...defaultOptions,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }
    @endif
});
</script>
@endsection
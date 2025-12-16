@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="header">
        <h1>Dashboard</h1>
        <p>Selamat datang kembali, {{ $user->name }}</p>
    </div>

    <div class="stats-grid">
        @if($user->role == 'Admin')
            <div class="stat-card">
                <div class="stat-label">Jumlah User</div>
                <div class="stat-value">{{ $totalUsers ?? 0 }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Jumlah Bus</div>
                <div class="stat-value">{{ $totalBus ?? 0 }}</div>
            </div>
        @elseif($user->role == 'Dispatcher')
            <div class="stat-card">
                <div class="stat-label">Bus Aktif</div>
                <div class="stat-value">{{ $activeBus ?? 0 }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Jadwal Hari Ini</div>
                <div class="stat-value">{{ $todaySchedule ?? 0 }}</div>
            </div>
        @elseif($user->role == 'Teknisi')
            <div class="stat-card">
                <div class="stat-label">Kerusakan Baru</div>
                <div class="stat-value">{{ $newDamage ?? 0 }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Sedang Diperbaiki</div>
                <div class="stat-value">{{ $inProgress ?? 0 }}</div>
            </div>
        @elseif($user->role == 'Manajer')
            <div class="stat-card">
                <div class="stat-label">Total Kerusakan</div>
                <div class="stat-value">{{ $totalDamage ?? 0 }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Kerusakan Selesai</div>
                <div class="stat-value">{{ $completedDamage ?? 0 }}</div>
            </div>
        @endif
    </div>
@endsection
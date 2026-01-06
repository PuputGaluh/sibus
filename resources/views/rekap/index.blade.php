@extends('layouts.app')

@section('title', 'Rekap Laporan')

@section('content')
<div class="page-container">
    <!-- Header Section -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <div class="icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                </div>
                <div>
                    <h1>Rekap Laporan</h1>
                    <p class="subtitle">Ringkasan laporan kerusakan bus listrik beserta tindak lanjut perbaikannya</p>
                </div>
            </div>
            <div class="action-group">
                <button onclick="openFilterModal()" class="btn-export btn-filter">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                    </svg>
                    Filter & Cetak
                </button>
            </div>
        </div>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon total">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
            </div>
            <div class="stat-content">
                <h3>{{ $data->count() }}</h3>
                <p>Total Laporan</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon pending">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </div>
            <div class="stat-content">
                <h3>{{ $data->whereIn('status_perbaikan', ['Belum Dijadwalkan', 'Pending'])->count() }}</h3>
                <p>Belum/Pending</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon progress">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
            </div>
            <div class="stat-content">
                <h3>{{ $data->whereIn('status_perbaikan', ['In Progress', 'Menunggu Validasi'])->count() }}</h3>
                <p>Dalam Proses</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon completed">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
            <div class="stat-content">
                <h3>{{ $data->where('status_perbaikan', 'Selesai')->count() }}</h3>
                <p>Selesai</p>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="filter-section">
        <div class="search-box">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.35-4.35"></path>
            </svg>
            <input type="text" id="searchInput" placeholder="Cari berdasarkan bus, teknisi, atau kategori...">
        </div>
        <select id="statusFilter" class="filter-select">
            <option value="">Semua Status</option>
            <option value="Belum Dijadwalkan">Belum Dijadwalkan</option>
            <option value="Pending">Pending</option>
            <option value="In Progress">In Progress</option>
            <option value="Menunggu Validasi">Menunggu Validasi</option>
            <option value="Selesai">Selesai</option>
        </select>
    </div>

    <!-- Table Section -->
    <div class="table-container">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>
                        <div class="th-content">
                            <span>NO</span>
                            <svg class="sort-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 5v14M5 12l7 7 7-7"/>
                            </svg>
                        </div>
                    </th>
                    <th>
                        <div class="th-content">
                            <span>Bus</span>
                            <svg class="sort-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 5v14M5 12l7 7 7-7"/>
                            </svg>
                        </div>
                    </th>
                    <th>Kategori</th>
                    <th>Tingkat</th>
                    <th>Tanggal Lapor</th>
                    <th>Lokasi</th>
                    <th>Status Perbaikan</th>
                    <th>Teknisi</th>
                    <th>Hasil Perbaikan</th>
                    <th>Tanggal Selesai</th>
                    <!-- <th>Aksi</th> -->
                </tr>
            </thead>
            <tbody id="rekapTable">
            @forelse($data as $index => $row)
                <tr class="rekap-row">
                    <td><span class="id-badge">{{ $index + 1 }}</span></td>
                    <td>
                        <div class="bus-info">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M8 6v6M16 6v6M3 16h18M3 10h18M5 20h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z"></path>
                            </svg>
                            <strong>{{ $row->nama_bus }}</strong>
                        </div>
                    </td>
                    <td>
                        <div class="category-cell">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                                <path d="M2 17l10 5 10-5M2 12l10 5 10-5"></path>
                            </svg>
                            {{ $row->nama_kategori }}
                        </div>
                    </td>
                    <td>
                        <span class="tingkat-badge tingkat-{{ strtolower(str_replace(' ', '-', $row->nama_tingkat)) }}">
                            {{ $row->nama_tingkat }}
                        </span>
                    </td>
                    <td>
                        <div class="date-cell">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            {{ $row->tanggal_laporan }}
                        </div>
                    </td>
                    <td>
                        <div class="date-cell">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M21 10c0 6-9 13-9 13S3 16 3 10a9 9 0 1 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            {{ $row->lokasi_nama ?? '-' }}
                        </div>
                    </td>
                    <td>
                        <span class="status-badge 
                            @if($row->status_perbaikan == 'Belum Dijadwalkan' || $row->status_perbaikan == 'Pending') status-pending
                            @elseif($row->status_perbaikan == 'In Progress' || $row->status_perbaikan == 'Menunggu Validasi') status-progress
                            @elseif($row->status_perbaikan == 'Selesai') status-selesai
                            @else status-default
                            @endif">
                            {{ $row->status_perbaikan ?? 'Belum Dijadwalkan' }}
                        </span>
                    </td>
                    <td>
                        <div class="teknisi-info">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            {{ $row->nama_teknisi ?? '-' }}
                        </div>
                    </td>
                    <td>
                        <div class="hasil-cell">
                            {{ $row->deskripsi_pekerjaan_teknisi ?? 'Belum ada perbaikan' }}
                        </div>
                    </td>
                    <td>
                        @if($row->tanggal_selesai_aktual)
                            <div class="date-cell">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                {{ $row->tanggal_selesai_aktual }}
                            </div>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <!-- <td>
                        <button onclick="viewDetail({{ $row->id_laporan }})" class="btn-detail">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            Detail
                        </button>
                    </td> -->
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="empty-state">
                        <div class="empty-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                            </svg>
                        </div>
                        <p>Data rekap laporan belum tersedia</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Filter Modal -->
<div id="filterModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Filter & Cetak Laporan</h2>
            <button class="close-modal" onclick="closeFilterModal()">&times;</button>
        </div>
        <form id="filterForm" action="{{ route('rekap.index') }}" method="GET">
            <div class="modal-body">
                <div class="form-group">
                    <label>Pilih Periode</label>
                    <select name="filter_type" id="filter_type" class="form-control" onchange="toggleFilterOptions()">
                        <option value="">Semua Data</option>
                        <option value="minggu">Minggu Ini</option>
                        <option value="bulan">Per Bulan</option>
                        <option value="tahun">Per Tahun</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>

                <!-- Bulan Filter -->
                <div id="bulan_filter" class="filter-options" style="display: none;">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Bulan</label>
                            <select name="bulan" class="form-control">
                                <option value="01">Januari</option>
                                <option value="02">Februari</option>
                                <option value="03">Maret</option>
                                <option value="04">April</option>
                                <option value="05">Mei</option>
                                <option value="06">Juni</option>
                                <option value="07">Juli</option>
                                <option value="08">Agustus</option>
                                <option value="09">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tahun</label>
                            <select name="tahun" class="form-control">
                                @for($i = date('Y'); $i >= 2020; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Tahun Filter -->
                <div id="tahun_filter" class="filter-options" style="display: none;">
                    <div class="form-group">
                        <label>Tahun</label>
                        <select name="tahun" class="form-control">
                            @for($i = date('Y'); $i >= 2020; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <!-- Custom Range Filter -->
                <div id="custom_filter" class="filter-options" style="display: none;">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Tanggal Akhir</label>
                            <input type="date" name="end_date" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn-modal btn-apply">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Terapkan Filter
                </button>
                <button type="button" class="btn-modal btn-pdf" onclick="exportPDF()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                    Cetak PDF
                </button>
                <button type="button" class="btn-modal btn-csv" onclick="exportCSV()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    Export CSV
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Detail Modal -->
<div id="detailModal" class="modal">
    <div class="modal-content modal-large">
        <div class="modal-header">
            <h2>Detail Laporan Kerusakan</h2>
            <button class="close-modal" onclick="closeDetailModal()">&times;</button>
        </div>
        <div class="modal-body" id="detailContent">
            <div class="loading-spinner">
                <div class="spinner"></div>
                <p>Memuat data...</p>
            </div>
        </div>
    </div>
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
        --shadow: rgba(220, 38, 38, 0.1);
        --shadow-lg: rgba(220, 38, 38, 0.15);
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: #e5e7eb;
        min-height: 100vh;
        padding: 0;
        margin: 0;
    }

    .page-container {
        max-width: 100%;
        margin: 0;
        padding: 1rem;
        animation: fadeIn 0.5s ease;
        min-height: 100vh;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
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
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
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
        flex: 1;
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
        line-height: 1.5;
    }

    .action-group {
        display: flex;
        gap: 0.75rem;
    }

    .btn-export {
        padding: 0.875rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        text-decoration: none;
        font-size: 0.95rem;
        border: none;
    }

    .btn-filter {
        background: linear-gradient(135deg, var(--primary), #c81e1e);
        color: white;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }

    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
    }

    .btn-pdf {
        background: linear-gradient(135deg, var(--danger), #c81e1e);
        color: white;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }

    .btn-pdf:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
    }

    .btn-csv {
        background: linear-gradient(135deg, var(--success), #059669);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-csv:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
    }

    /* Alert */
    .alert {
        background: white;
        border-left: 4px solid var(--success);
        border-radius: 10px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        animation: slideDown 0.5s ease;
        color: var(--success);
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
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        animation: scaleIn 0.5s ease;
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
    }

    .stat-icon.total {
        background: linear-gradient(135deg, #64748b, #475569);
    }

    .stat-icon.pending {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .stat-icon.progress {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    }

    .stat-icon.completed {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .stat-content h3 {
        font-size: 2rem;
        color: var(--dark);
        margin-bottom: 0.25rem;
    }

    .stat-content p {
        color: var(--secondary);
        font-size: 0.9rem;
    }

    /* Filter Section */
    .filter-section {
        background: white;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.25rem;
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .search-box {
        flex: 1;
        min-width: 300px;
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-box svg {
        position: absolute;
        left: 1rem;
        color: var(--secondary);
    }

    .search-box input {
        width: 100%;
        padding: 0.875rem 1rem 0.875rem 3rem;
        border: 2px solid var(--border);
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    .filter-select {
        padding: 0.875rem 1.25rem;
        border: 2px solid var(--border);
        border-radius: 10px;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    /* Table */
    .table-container {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .modern-table {
        width: 100%;
        border-collapse: collapse;
    }

    .modern-table thead {
        background: linear-gradient(135deg, #dc2626, #991b1b);
        color: white;
    }

    .modern-table th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .th-content {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .sort-icon {
        opacity: 0.5;
        transition: all 0.3s ease;
    }

    .th-content:hover .sort-icon {
        opacity: 1;
    }

    .modern-table tbody tr {
        border-bottom: 1px solid var(--border);
        transition: all 0.3s ease;
    }

    .modern-table tbody tr:hover {
        background: #f8fafc;
        transform: scale(1.01);
    }

    .modern-table td {
        padding: 1rem;
        font-size: 0.95rem;
    }

    .id-badge {
        background: linear-gradient(135deg, #dc2626, #991b1b);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .bus-info, .teknisi-info, .date-cell, .category-cell {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--dark);
    }

    .bus-info svg, .teknisi-info svg, .date-cell svg, .category-cell svg {
        color: var(--secondary);
        flex-shrink: 0;
    }

    .tingkat-badge {
        display: inline-block;
        padding: 0.4rem 0.875rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .tingkat-ringan {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .tingkat-sedang {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }


    .tingkat-tinggi {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .status-pending {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    .status-progress {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
    }

    .status-selesai {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .status-default {
        background: linear-gradient(135deg, #64748b, #475569);
        color: white;
    }

    .hasil-cell {
        max-width: 250px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: var(--secondary);
    }

    .text-muted {
        color: var(--secondary);
        font-style: italic;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-icon {
        margin-bottom: 1rem;
        color: var(--secondary);
        opacity: 0.5;
    }

    .empty-state p {
        color: var(--secondary);
        font-size: 1rem;
    }

    /* Button Detail */
    .btn-detail {
        background: linear-gradient(135deg, var(--info), #1d4ed8);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.3s ease;
    }

    .btn-detail:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        animation: fadeIn 0.3s ease;
    }

    .modal-content {
        background-color: white;
        margin: 5% auto;
        border-radius: 16px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s ease;
    }

    .modal-large {
        max-width: 900px;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-header {
        padding: 1.5rem;
        border-bottom: 2px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h2 {
        color: var(--dark);
        font-size: 1.5rem;
        margin: 0;
    }

    .close-modal {
        background: none;
        border: none;
        font-size: 2rem;
        color: var(--secondary);
        cursor: pointer;
        line-height: 1;
        transition: all 0.3s ease;
    }

    .close-modal:hover {
        color: var(--danger);
        transform: rotate(90deg);
    }

    .modal-body {
        padding: 1.5rem;
        max-height: 60vh;
        overflow-y: auto;
    }

    .modal-footer {
        padding: 1.5rem;
        border-top: 2px solid var(--border);
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    /* Form Styles */
    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--dark);
        font-weight: 600;
        font-size: 0.95rem;
    }

    .form-control {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid var(--border);
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .btn-modal {
        padding: 0.875rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        font-size: 0.95rem;
        border: none;
    }

    .btn-apply {
        background: linear-gradient(135deg, var(--primary), #c81e1e);
        color: white;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }

    .btn-apply:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
    }

    /* Loading Spinner */
    .loading-spinner {
        text-align: center;
        padding: 3rem;
    }

    .spinner {
        border: 4px solid rgba(220, 38, 38, 0.1);
        border-radius: 50%;
        border-top: 4px solid var(--primary);
        width: 60px;
        height: 60px;
        animation: spin 1s linear infinite;
        margin: 0 auto 1rem;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Detail Content */
    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .detail-section {
        background: var(--light);
        padding: 1.25rem;
        border-radius: 12px;
        border-left: 4px solid var(--primary);
    }

    .detail-section h3 {
        color: var(--dark);
        font-size: 1.1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .detail-item {
        margin-bottom: 1rem;
    }

    .detail-item:last-child {
        margin-bottom: 0;
    }

    .detail-label {
        color: var(--secondary);
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }

    .detail-value {
        color: var(--dark);
        font-size: 1rem;
        font-weight: 500;
    }

    .detail-full {
        grid-column: 1 / -1;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .stats-container {
            grid-template-columns: repeat(2, 1fr);
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

        .action-group {
            width: 100%;
        }

        .btn-export {
            flex: 1;
            justify-content: center;
        }

        .stats-container {
            grid-template-columns: 1fr;
        }

        .filter-section {
            flex-direction: column;
        }

        .search-box {
            min-width: 100%;
        }

        .table-container {
            overflow-x: auto;
        }

        .modern-table {
            min-width: 1200px;
        }

        .modal-content {
            width: 95%;
            margin: 10% auto;
        }

        .detail-grid {
            grid-template-columns: 1fr;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .modal-footer {
            flex-direction: column;
        }

        .btn-modal {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<script>
    // Search and filter functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        filterTable();
    });

    document.getElementById('statusFilter').addEventListener('change', function() {
        filterTable();
    });

    function filterTable() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const rows = document.querySelectorAll('.rekap-row');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const statusCell = row.querySelector('.status-badge');
            const status = statusCell ? statusCell.textContent.trim() : '';

            const matchesSearch = text.includes(searchTerm);
            const matchesStatus = !statusFilter || status === statusFilter;

            if (matchesSearch && matchesStatus) {
                row.style.display = '';
                row.style.animation = 'fadeIn 0.3s ease';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Table sorting
    document.querySelectorAll('.th-content').forEach(th => {
        th.addEventListener('click', function() {
            const table = this.closest('table');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr:not(.empty-state)'));
            const index = Array.from(this.closest('tr').children).indexOf(this.closest('th'));
            
            rows.sort((a, b) => {
                const aText = a.children[index].textContent.trim();
                const bText = b.children[index].textContent.trim();
                return aText.localeCompare(bText);
            });

            if (this.dataset.sorted === 'asc') {
                rows.reverse();
                this.dataset.sorted = 'desc';
            } else {
                this.dataset.sorted = 'asc';
            }

            rows.forEach(row => tbody.appendChild(row));
        });
    });

    // Auto-hide alert
    window.addEventListener('load', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.animation = 'fadeOut 0.5s ease';
                setTimeout(() => alert.remove(), 500);
            }, 5000);
        });
    });

    // Add fadeOut animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(-20px);
            }
        }
    `;
    document.head.appendChild(style);

    // Filter Modal Functions
    function openFilterModal() {
        document.getElementById('filterModal').style.display = 'block';
    }

    function closeFilterModal() {
        document.getElementById('filterModal').style.display = 'none';
    }

    function toggleFilterOptions() {
        const filterType = document.getElementById('filter_type').value;
        
        // Hide all options first
        document.querySelectorAll('.filter-options').forEach(el => {
            el.style.display = 'none';
        });

        // Show selected option
        if (filterType === 'bulan') {
            document.getElementById('bulan_filter').style.display = 'block';
        } else if (filterType === 'tahun') {
            document.getElementById('tahun_filter').style.display = 'block';
        } else if (filterType === 'custom') {
            document.getElementById('custom_filter').style.display = 'block';
        }
    }

    function exportPDF() {
        const form = document.getElementById('filterForm');
        const url = new URL('{{ route("rekap.export.pdf") }}', window.location.origin);
        
        // Get form data
        const formData = new FormData(form);
        formData.forEach((value, key) => {
            if (value) url.searchParams.append(key, value);
        });
        
        window.location.href = url.toString();
    }

    function exportCSV() {
        const form = document.getElementById('filterForm');
        const url = new URL('{{ route("rekap.export.csv") }}', window.location.origin);
        
        // Get form data
        const formData = new FormData(form);
        formData.forEach((value, key) => {
            if (value) url.searchParams.append(key, value);
        });
        
        window.location.href = url.toString();
    }

    // Detail Modal Functions
    function viewDetail(id) {
        console.log('Fetching detail for ID:', id); // Debug log
        document.getElementById('detailModal').style.display = 'block';
        
        // Reset content to loading state
        document.getElementById('detailContent').innerHTML = `
            <div class="loading-spinner">
                <div class="spinner"></div>
                <p>Memuat data...</p>
            </div>
        `;
        
        // Fetch detail data
        fetch(`/rekap/detail/${id}`)
            .then(response => {
                console.log('Response status:', response.status); // Debug log
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Data received:', data); // Debug log
                if (data.error) {
                    throw new Error(data.error);
                }
                displayDetailData(data);
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('detailContent').innerHTML = `
                    <div style="text-align: center; padding: 3rem;">
                        <div style="color: var(--danger); margin-bottom: 1rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                        </div>
                        <h3 style="color: var(--danger); margin-bottom: 0.5rem;">Gagal Memuat Data</h3>
                        <p style="color: var(--secondary);">${error.message}</p>
                        <button onclick="viewDetail(${id})" class="btn-modal btn-apply" style="margin-top: 1rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="23 4 23 10 17 10"></polyline>
                                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
                            </svg>
                            Coba Lagi
                        </button>
                    </div>
                `;
            });
    }

    function closeDetailModal() {
        document.getElementById('detailModal').style.display = 'none';
    }

    function displayDetailData(data) {
        const content = `
            <div class="detail-grid">
                <div class="detail-section">
                    <h3>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                        </svg>
                        Informasi Laporan
                    </h3>
                    <div class="detail-item">
                        <div class="detail-label">ID Laporan</div>
                        <div class="detail-value">#${data.id_laporan}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Bus</div>
                        <div class="detail-value">${data.nama_bus || '-'}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Kategori Kerusakan</div>
                        <div class="detail-value">${data.nama_kategori || '-'}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Tingkat Kerusakan</div>
                        <div class="detail-value">
                            <span class="tingkat-badge tingkat-${(data.nama_tingkat || '').toLowerCase().replace(' ', '-')}">${data.nama_tingkat || '-'}</span>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Pelapor</div>
                        <div class="detail-value">${data.nama_pelapor || '-'}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Tanggal Lapor</div>
                        <div class="detail-value">${formatDate(data.tanggal_laporan)}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Lokasi</div>
                        <div class="detail-value">${data.lokasi_nama || '-'}</div>
                    </div>
                </div>

                <div class="detail-section">
                    <h3>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                        </svg>
                        Informasi Perbaikan
                    </h3>
                    <div class="detail-item">
                        <div class="detail-label">Status Perbaikan</div>
                        <div class="detail-value">
                            <span class="status-badge ${getStatusClass(data.status_perbaikan)}">${data.status_perbaikan || 'Belum Dijadwalkan'}</span>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Teknisi</div>
                        <div class="detail-value">${data.nama_teknisi || '-'}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Tanggal Selesai</div>
                        <div class="detail-value">${data.tanggal_selesai_aktual ? formatDate(data.tanggal_selesai_aktual) : '-'}</div>
                    </div>
                </div>

                <div class="detail-section detail-full">
                    <h3>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                        </svg>
                        Deskripsi Kerusakan
                    </h3>
                    <div class="detail-item">
                        <div class="detail-value" style="white-space: pre-wrap;">${data.deskripsi_kerusakan || '-'}</div>
                    </div>
                </div>

                ${data.deskripsi_pekerjaan_teknisi ? `
                <div class="detail-section detail-full">
                    <h3>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        Hasil Perbaikan
                    </h3>
                    <div class="detail-item">
                        <div class="detail-value" style="white-space: pre-wrap;">${data.deskripsi_pekerjaan_teknisi}</div>
                    </div>
                </div>
                ` : ''}
            </div>
        `;

        document.getElementById('detailContent').innerHTML = content;
    }

    function formatDate(dateString) {
        if (!dateString) return '-';
        try {
            const date = new Date(dateString);
            return date.toLocaleString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        } catch (e) {
            return dateString;
        }
    }

    function getStatusClass(status) {
        if (!status || status === 'Belum Dijadwalkan' || status === 'Pending') {
            return 'status-pending';
        } else if (status === 'In Progress' || status === 'Menunggu Validasi') {
            return 'status-progress';
        } else if (status === 'Selesai') {
            return 'status-selesai';
        }
        return 'status-default';
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        const filterModal = document.getElementById('filterModal');
        const detailModal = document.getElementById('detailModal');
        
        if (event.target == filterModal) {
            closeFilterModal();
        }
        if (event.target == detailModal) {
            closeDetailModal();
        }
    }
</script>
@endsection
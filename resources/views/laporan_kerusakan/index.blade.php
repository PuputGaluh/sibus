@extends('layouts.app')

@section('title', 'Laporan Kerusakan')

@section('content')
<div class="page-container">
    <!-- Header Section -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <div class="icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                        <line x1="12" y1="9" x2="12" y2="13"></line>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                </div>
                <div>
                    <h1>Laporan Kerusakan</h1>
                    <p class="subtitle">Monitor dan kelola laporan kerusakan bus</p>
                </div>
            </div>
            @if(auth()->user()->role === 'Teknisi')
                <button class="btn-add" onclick="document.getElementById('dialogTambahLaporan').showModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Tambah Laporan Kerusakan
                </button>
            @endif
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
            <div class="stat-icon warning">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>
            <div class="stat-content">
                <h3>{{ $laporan->where('status_proses', 'Dilaporkan')->count() }}</h3>
                <p>Dilaporkan</p>
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
                <h3>{{ $laporan->where('status_proses', 'Validasi Diproses')->count() }}</h3>
                <p>Validasi Diproses</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon success">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <div class="stat-content">
                <h3>{{ $laporan->where('status_proses', 'Selesai')->count() }}</h3>
                <p>Selesai</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon total">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="12" y1="18" x2="12" y2="12"></line>
                    <line x1="9" y1="15" x2="15" y2="15"></line>
                </svg>
            </div>
            <div class="stat-content">
                <h3>{{ $laporan->count() }}</h3>
                <p>Total Laporan</p>
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
            <input type="text" id="searchInput" placeholder="Cari berdasarkan nama bus atau pelapor...">
        </div>
        <select id="statusFilter" class="filter-select">
            <option value="">Semua Status</option>
            <option value="Dilaporkan">Dilaporkan</option>
            <option value="Validasi Diproses">Validasi Diproses</option>
            <option value="Selesai">Selesai</option>
        </select>
        <select id="keberangkatanFilter" class="filter-select">
            <option value="">Status Keberangkatan</option>
            <option value="Pool">Pool</option>
            <option value="Akan Berangkat">Akan Berangkat</option>
            <option value="Perjalanan">Perjalanan</option>
        </select>
    </div>

    <!-- Table Section -->
    <div class="table-container">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>
                        <div class="th-content">
                            <span>ID</span>
                            <svg class="sort-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 5v14M5 12l7 7 7-7"/>
                            </svg>
                        </div>
                    </th>
                    <th>
                        <div class="th-content">
                            <span>Nama Bus</span>
                            <svg class="sort-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 5v14M5 12l7 7 7-7"/>
                            </svg>
                        </div>
                    </th>
                    <th>
                        <div class="th-content">
                            <span>Pelapor</span>
                            <svg class="sort-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 5v14M5 12l7 7 7-7"/>
                            </svg>
                        </div>
                    </th>
                    <th>Tanggal Lapor</th>
                    <th>Status Keberangkatan</th>
                    <th>Status Proses</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="laporanTableBody">
                @forelse($laporan as $item)
                <tr class="laporan-row" data-laporan-id="{{ $item->id_laporan }}">
                    <td><span class="id-badge">#{{ $item->id_laporan }}</span></td>
                    <td>
                        <div class="bus-info">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M8 6v6M16 6v6M3 16h18M3 10h18M5 20h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z"></path>
                            </svg>
                            <strong>{{ $item->bus->nama_bus ?? '-' }}</strong>
                        </div>
                    </td>
                    <td>
                        <div class="pelapor-info">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            {{ $item->pelapor->name ?? '-' }}
                        </div>
                    </td>
                    <td>
                        <div class="date-cell">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            {{ $item->tanggal_lapor }}
                        </div>
                    </td>
                    <td>
                        <span class="status-keberangkatan status-{{ strtolower(str_replace(' ', '-', $item->status_keberangkatan)) }}">
                            {{ $item->status_keberangkatan }}
                        </span>
                    </td>
                    <td>
                        <span class="status-badge 
                            @if($item->status_proses == 'Dilaporkan') status-dilaporkan
                            @elseif($item->status_proses == 'Validasi Diproses') status-validasi
                            @else status-selesai
                            @endif">
                            {{ $item->status_proses }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-action btn-view" 
                                    onclick="lihatDetail({{ $item->id_laporan }})"
                                    title="Lihat Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>

                            @if(auth()->user()->role === 'Dispatcher' && $item->status_proses === 'Dilaporkan')
                                <form action="{{ route('laporan.validasi', $item->id_laporan) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button class="btn-action btn-validate" type="submit" title="Validasi Laporan">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    </button>
                                </form>
                            @endif

                            @if(auth()->user()->role === 'Dispatcher' && $item->status_proses === 'Validasi Diproses')
                                <button class="btn-action btn-schedule"
                                    title="Jadwalkan Perbaikan"
                                    onclick="openJadwalModal({{ $item->id_laporan }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="empty-state">
                        <div class="empty-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                            </svg>
                        </div>
                        <p>Data laporan belum tersedia</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ================= DIALOG TAMBAH LAPORAN ================= --}}
<dialog id="dialogTambahLaporan" class="modern-dialog">
    <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data" class="dialog-form">
        @csrf
        <div class="dialog-header">
            <div class="dialog-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="12" y1="18" x2="12" y2="12"></line>
                    <line x1="9" y1="15" x2="15" y2="15"></line>
                </svg>
                <h5>Form Laporan Kerusakan</h5>
            </div>
            <button type="button" class="btn-close" onclick="document.getElementById('dialogTambahLaporan').close()">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div class="dialog-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="add_bus">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M8 6v6M16 6v6M3 16h18M3 10h18M5 20h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z"></path>
                        </svg>
                        Nama Bus
                    </label>
                    <select id="add_bus" class="form-input" name="id_bus" required>
                        <option value="">Pilih Bus</option>
                        @foreach($bus as $b)
                            <option value="{{ $b->id_bus }}">{{ $b->nama_bus }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="add_status_keberangkatan">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        Status Keberangkatan
                    </label>
                    <select id="add_status_keberangkatan" class="form-input" name="status_keberangkatan" required>
                        <option value="">Pilih Status</option>
                        <option value="Pool">Pool</option>
                        <option value="Akan Berangkat">Akan Berangkat</option>
                        <option value="Perjalanan">Perjalanan</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="add_kategori">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                            <path d="M2 17l10 5 10-5M2 12l10 5 10-5"></path>
                        </svg>
                        Kategori Kerusakan
                    </label>
                    <select id="add_kategori" class="form-input" name="id_kategori" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($kategori as $k)
                            <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="add_tingkat">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                            <line x1="12" y1="9" x2="12" y2="13"></line>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                        Tingkat Kerusakan
                    </label>
                    <select id="add_tingkat" class="form-input" name="id_tingkat" required>
                        <option value="">Pilih Tingkat</option>
                        @foreach($tingkat as $t)
                            <option value="{{ $t->id_tingkat }}">{{ $t->nama_tingkat }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="add_keterangan">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    Keterangan
                </label>
                <textarea id="add_keterangan" class="form-input" name="keterangan" rows="4" placeholder="Deskripsikan kerusakan yang terjadi..."></textarea>
            </div>

            <div class="form-group">
                <label for="add_foto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                    Foto Kerusakan
                </label>
                <div class="file-input-wrapper">
                    <input type="file" id="add_foto" class="form-input-file" name="foto" accept="image/*">
                    <label for="add_foto" class="file-input-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                        <span class="file-name">Pilih file atau drag & drop</span>
                    </label>
                </div>
                <div id="imagePreview" class="image-preview"></div>
            </div>
        </div>
        <div class="dialog-footer">
            <button type="button" class="btn-secondary" onclick="document.getElementById('dialogTambahLaporan').close()">
                Batal
            </button>
            <button type="submit" class="btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                Laporkan Kerusakan
            </button>
        </div>
    </form>
</dialog>

{{-- ================= DIALOG DETAIL (ENHANCED) ================= --}}
<dialog id="dialogDetail" class="modern-dialog detail-dialog">
    <div class="dialog-header">
        <div class="dialog-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="16" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
            <h5>Detail Laporan Kerusakan</h5>
        </div>
        <button type="button" class="btn-close" onclick="document.getElementById('dialogDetail').close()">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>
    <div class="dialog-body" id="detailContent">
        <div class="loading-spinner">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="2" x2="12" y2="6"></line>
                <line x1="12" y1="18" x2="12" y2="22"></line>
                <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
                <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
                <line x1="2" y1="12" x2="6" y2="12"></line>
                <line x1="18" y1="12" x2="22" y2="12"></line>
                <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
                <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
            </svg>
            <p>Memuat data...</p>
        </div>
    </div>
</dialog>

{{-- ================= IMAGE ZOOM MODAL ================= --}}
<dialog id="imageZoomModal" class="zoom-modal">
    <div class="zoom-modal-content">
        <button type="button" class="zoom-close" onclick="closeZoomModal()">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
        <img id="zoomedImage" src="" alt="Zoomed Image">
        <div class="zoom-controls">
            <button type="button" class="zoom-btn" onclick="zoomIn()">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    <line x1="11" y1="8" x2="11" y2="14"></line>
                    <line x1="8" y1="11" x2="14" y2="11"></line>
                </svg>
            </button>
            <button type="button" class="zoom-btn" onclick="zoomOut()">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    <line x1="8" y1="11" x2="14" y2="11"></line>
                </svg>
            </button>
            <button type="button" class="zoom-btn" onclick="resetZoom()">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="1 4 1 10 7 10"></polyline>
                    <polyline points="23 20 23 14 17 14"></polyline>
                    <path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"></path>
                </svg>
            </button>
        </div>
    </div>
</dialog>

{{-- ================= DIALOG JADWALKAN PERBAIKAN WITH CALENDAR ================= --}}
<dialog id="dialogJadwal" class="modern-dialog jadwal-dialog">
    <form method="POST" id="formJadwal" class="dialog-form">
        @csrf
        <div class="dialog-header">
            <div class="dialog-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <h5>Jadwalkan Perbaikan</h5>
            </div>
            <button type="button" class="btn-close" onclick="closeJadwalModal()">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <div class="dialog-body">
            <!-- Teknisi Selection -->
            <div class="form-group">
                <label for="id_teknisi">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Pilih Teknisi
                </label>
                <select name="id_teknisi" id="id_teknisi" class="form-input" required onchange="loadTeknisiSchedule(this.value)">
                    <option value="">Pilih Teknisi</option>
                    @foreach($teknisi as $t)
                        <option value="{{ $t->id_user }}">{{ $t->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Calendar Section -->
            <div id="calendarSection" class="calendar-section" style="display: none;">
                <div class="calendar-header">
                    <button type="button" class="calendar-nav-btn" onclick="prevMonth()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </button>
                    <div class="calendar-month-year" id="calendarMonthYear"></div>
                    <button type="button" class="calendar-nav-btn" onclick="nextMonth()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </button>
                </div>
                <div class="calendar-grid" id="calendarGrid"></div>
                
                <!-- Legend -->
                <div class="calendar-legend">
                    <div class="legend-item">
                        <span class="legend-color available"></span>
                        <span>Tersedia</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-color busy"></span>
                        <span>Sibuk</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-color selected"></span>
                        <span>Dipilih</span>
                    </div>
                </div>
            </div>

            <!-- Hidden inputs untuk menyimpan tanggal -->
            <input type="hidden" name="tanggal_mulai_estimasi" id="tanggal_mulai_estimasi" required>
            <input type="hidden" name="tanggal_selesai_estimasi" id="tanggal_selesai_estimasi" required>

            <!-- Card untuk menampilkan tanggal yang dipilih -->
            <div id="selectedDateDisplay" class="selected-date-display" style="display: none;">
                <div class="date-display-card">
                    <div class="date-display-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <div class="date-display-content">
                        <div class="date-display-row">
                            <div>
                                <div class="date-display-label">Tanggal Mulai</div>
                                <div class="date-display-value" id="startDateText">-</div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>                            
                            <div>
                                <div class="date-display-label">Tanggal Selesai</div>
                                <div class="date-display-value" id="endDateText">-</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Priority (Auto-filled) -->
            <div class="form-group">
                <label>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                        <line x1="12" y1="9" x2="12" y2="13"></line>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                    Prioritas Perbaikan
                </label>
                <input type="text" id="prioritas_auto" class="form-input" readonly>
                <input type="hidden" name="prioritas" id="prioritas_hidden">
            </div>

            <!-- Notes -->
            <div class="form-group">
                <label>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    Catatan Dispatcher
                </label>
                <textarea name="catatan" class="form-input" rows="3" placeholder="Catatan untuk teknisi..."></textarea>
            </div>
        </div>

        <div class="dialog-footer">
            <button type="button" class="btn-secondary" onclick="closeJadwalModal()">
                Batal
            </button>
            <button type="submit" class="btn-primary" id="submitBtn" disabled>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                Simpan Jadwal
            </button>
        </div>
    </form>
</dialog>

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
        --calendar-available: #10b981;
        --calendar-busy: #94a3b8;
        --calendar-selected: #dc2626;
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

    .btn-add {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        border: none;
        padding: 0.875rem 1.75rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
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

    .stat-icon.warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .stat-icon.info {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    }

    .stat-icon.success {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .stat-icon.total {
        background: linear-gradient(135deg, #64748b, #475569);
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

    .bus-info, .pelapor-info, .date-cell {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--dark);
    }

    .bus-info svg, .pelapor-info svg, .date-cell svg {
        color: var(--secondary);
    }

    .status-keberangkatan {
        display: inline-block;
        padding: 0.4rem 0.875rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .status-pool {
        background: linear-gradient(135deg, #64748b, #475569);
        color: white;
    }

    .status-akan-berangkat {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    .status-perjalanan {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
    }

    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .status-dilaporkan {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    .status-validasi {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
    }

    .status-selesai {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        width: 36px;
        height: 36px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .btn-view {
        background: #dbeafe;
        color: #3b82f6;
    }

    .btn-view:hover {
        background: #93c5fd;
        transform: scale(1.1);
    }

    .btn-validate {
        background: #d1fae5;
        color: #10b981;
    }

    .btn-validate:hover {
        background: #6ee7b7;
        transform: scale(1.1);
    }

    .btn-schedule {
        background: #fef3c7;
        color: #f59e0b;
    }

    .btn-schedule:hover {
        background: #fcd34d;
        transform: scale(1.1);
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

    /* Dialog */
    .modern-dialog {
        border: none;
        border-radius: 16px;
        padding: 0;
        max-width: 600px;
        width: 90%;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        animation: dialogSlideIn 0.3s ease;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        margin: 0;
    }

    .detail-dialog {
        max-width: 800px;
    }

    .jadwal-dialog {
        max-width: 700px;
    }

    @keyframes dialogSlideIn {
        from {
            opacity: 0;
            transform: translate(-50%, -50%) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }
    }

    .modern-dialog::backdrop {
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
    }

    .dialog-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        border-bottom: 1px solid var(--border);
    }

    .dialog-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: var(--primary);
    }

    .dialog-title h5 {
        margin: 0;
        font-size: 1.25rem;
        color: var(--dark);
    }

    .btn-close {
        width: 36px;
        height: 36px;
        border: none;
        background: #f1f5f9;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--secondary);
        transition: all 0.3s ease;
    }

    .btn-close:hover {
        background: #e2e8f0;
        color: var(--dark);
        transform: rotate(90deg);
    }

    .dialog-body {
        padding: 1.5rem;
        max-height: 70vh;
        overflow-y: auto;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
        color: var(--dark);
        font-weight: 600;
        font-size: 0.9rem;
    }

    .form-input {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid var(--border);
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    textarea.form-input {
        resize: vertical;
        min-height: 100px;
    }

    .file-input-wrapper {
        position: relative;
    }

    .form-input-file {
        display: none;
    }

    .file-input-label {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 1.5rem;
        border: 2px dashed var(--border);
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #f8fafc;
        color: var(--secondary);
        font-weight: 500;
    }

    .file-input-label:hover {
        border-color: var(--primary);
        background: #fff;
        color: var(--primary);
    }

    .image-preview {
        margin-top: 1rem;
        border-radius: 10px;
        overflow: hidden;
        display: none;
    }

    .image-preview img {
        width: 100%;
        height: auto;
        display: block;
    }

    .dialog-footer {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        padding: 1.5rem;
        border-top: 1px solid var(--border);
        background: #f8fafc;
    }

    .btn-primary, .btn-secondary {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }

    .btn-primary:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
    }

    .btn-primary:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .btn-secondary {
        background: #e2e8f0;
        color: var(--secondary);
    }

    .btn-secondary:hover {
        background: #cbd5e1;
    }

    /* ================= CALENDAR STYLES ================= */
    .calendar-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 2px solid var(--border);
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--border);
    }

    .calendar-month-year {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--dark);
        text-align: center;
        flex: 1;
    }

    .calendar-nav-btn {
        width: 40px;
        height: 40px;
        border: none;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        border-radius: 10px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(220, 38, 38, 0.2);
    }

    .calendar-nav-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4);
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .calendar-day-header {
        text-align: center;
        font-weight: 700;
        font-size: 0.85rem;
        color: var(--secondary);
        padding: 0.75rem 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .calendar-day {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        border: 2px solid transparent;
    }

    .calendar-day.empty {
        cursor: default;
        opacity: 0;
    }

    .calendar-day.past {
        color: #cbd5e1;
        cursor: not-allowed;
        background: #f8fafc;
    }

    .calendar-day.available {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
        border-color: var(--calendar-available);
    }

    .calendar-day.available:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        border-color: #059669;
    }

    .calendar-day.busy {
        background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
        color: #475569;
        border-color: var(--calendar-busy);
        cursor: not-allowed;
    }

    .calendar-day.selected {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        border-color: var(--calendar-selected);
        box-shadow: 0 4px 16px rgba(220, 38, 38, 0.4);
        transform: scale(1.05);
    }

    .calendar-day.today {
        position: relative;
    }

    .calendar-day.today::after {
        content: '';
        position: absolute;
        bottom: 4px;
        left: 50%;
        transform: translateX(-50%);
        width: 6px;
        height: 6px;
        background: var(--primary);
        border-radius: 50%;
    }

    .calendar-day.today.selected::after {
        background: white;
    }

    /* Calendar Legend */
    .calendar-legend {
        display: flex;
        gap: 1.5rem;
        justify-content: center;
        flex-wrap: wrap;
        padding-top: 1rem;
        border-top: 2px solid var(--border);
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: var(--secondary);
        font-weight: 500;
    }

    .legend-color {
        width: 24px;
        height: 24px;
        border-radius: 6px;
        border: 2px solid rgba(0, 0, 0, 0.1);
    }

    .legend-color.available {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        border-color: var(--calendar-available);
    }

    .legend-color.busy {
        background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
        border-color: var(--calendar-busy);
    }

    .legend-color.selected {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        border-color: var(--calendar-selected);
    }

    /* Loading Spinner */
    .loading-spinner {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem;
        gap: 1rem;
    }

    .loading-spinner svg {
        color: var(--primary);
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }

    .loading-spinner p {
        color: var(--secondary);
        font-size: 0.95rem;
    }

    /* Image Zoom Modal */
    .zoom-modal {
        border: none;
        padding: 0;
        max-width: none;
        max-height: none;
        width: 100vw;
        height: 100vh;
        background: transparent;
        position: fixed;
        top: 0;
        left: 0;
        transform: none;
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .zoom-modal[open] {
        display: flex;
    }

    .zoom-modal::backdrop {
        background: rgba(0, 0, 0, 0.95);
        backdrop-filter: blur(8px);
    }

    .zoom-modal-content {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        padding: 100px 40px;
    }

    .zoom-close {
        position: fixed;
        top: 20px;
        right: 20px;
        width: 48px;
        height: 48px;
        border: none;
        background: rgba(220, 38, 38, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        transition: all 0.3s ease;
        z-index: 1000;
        box-shadow: 0 4px 20px rgba(220, 38, 38, 0.6);
    }

    .zoom-close:hover {
        background: rgba(185, 28, 28, 1);
        transform: rotate(90deg) scale(1.15);
        box-shadow: 0 6px 24px rgba(220, 38, 38, 0.8);
    }

    #zoomedImage {
        max-width: calc(100vw - 80px);
        max-height: calc(100vh - 180px);
        width: auto;
        height: auto;
        object-fit: contain;
        border-radius: 12px;
        transition: transform 0.3s ease;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.9);
        background: white;
        padding: 8px;
    }

    .zoom-controls {
        position: fixed;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 0.75rem;
        background: rgba(15, 23, 42, 0.95);
        backdrop-filter: blur(10px);
        padding: 0.875rem 1.25rem;
        border-radius: 50px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .zoom-btn {
        width: 44px;
        height: 44px;
        border: none;
        background: rgba(220, 38, 38, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        transition: all 0.3s ease;
    }

    .zoom-btn:hover {
        background: rgba(185, 28, 28, 1);
        transform: scale(1.15);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.6);
    }

    /* Detail Dialog Styles */
    .detail-header-card {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        padding: 1.5rem;
        border-radius: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        color: white;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }

    .detail-id-section {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .detail-id-icon {
        width: 56px;
        height: 56px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(10px);
    }

    .detail-label {
        font-size: 0.85rem;
        opacity: 0.9;
        display: block;
        margin-bottom: 0.25rem;
    }

    .detail-id {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0;
    }

    .detail-status-badge {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        border-radius: 20px;
        font-weight: 600;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .detail-card {
        background: #f8fafc;
        padding: 1.25rem;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
    }

    .detail-card:hover {
        background: #f1f5f9;
        transform: translateY(-2px);
    }

    .highlight-card {
        border-left: 4px solid var(--primary);
    }

    .detail-card-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
    }

    .bus-icon {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    }

    .pelapor-icon {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    }

    .date-icon {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .category-icon {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .keberangkatan-icon {
        background: linear-gradient(135deg, #64748b, #475569);
    }

    .detail-card .detail-label {
        color: var(--secondary);
        font-size: 0.85rem;
        display: block;
        margin-bottom: 0.25rem;
    }

    .detail-value {
        color: var(--dark);
        font-weight: 600;
        font-size: 1rem;
        margin: 0;
    }

    .detail-section {
        margin-bottom: 1.5rem;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--border);
    }

    .section-header svg {
        color: var(--primary);
    }

    .section-header h4 {
        margin: 0;
        font-size: 1.1rem;
        color: var(--dark);
    }

    .tingkat-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
    }

    .tingkat-rendah {
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

    .detail-text-box {
        background: #f8fafc;
        padding: 1.25rem;
        border-radius: 10px;
        border: 2px solid var(--border);
    }

    .detail-text-box p {
        margin: 0;
        color: var(--dark);
        line-height: 1.6;
    }

    .detail-image-wrapper {
        background: #f8fafc;
        padding: 1rem;
        border-radius: 10px;
        border: 2px solid var(--border);
    }

    .detail-image {
        border-radius: 8px;
        overflow: hidden;
        cursor: zoom-in;
        position: relative;
        transition: all 0.3s ease;
    }

    .detail-image:hover {
        transform: scale(1.02);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    .detail-image img {
        width: 100%;
        height: auto;
        display: block;
    }

    .zoom-hint {
        text-align: center;
        margin-top: 0.75rem;
        color: var(--secondary);
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .zoom-hint svg {
        width: 16px;
        height: 16px;
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

        .btn-add {
            width: 100%;
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
            min-width: 900px;
        }

        .form-row, .detail-grid {
            grid-template-columns: 1fr;
        }

        .calendar-grid {
            gap: 0.25rem;
        }

        .calendar-day {
            font-size: 0.85rem;
        }

        .calendar-legend {
            gap: 1rem;
        }

        .detail-header-card {
            flex-direction: column;
            gap: 1rem;
        }

        .zoom-close {
            top: 15px;
            right: 15px;
            width: 44px;
            height: 44px;
        }

        .zoom-modal-content {
            padding: 70px 15px;
        }

        #zoomedImage {
            max-width: calc(100vw - 30px);
            max-height: calc(100vh - 160px);
            padding: 4px;
        }

        .zoom-controls {
            bottom: 20px;
            padding: 0.625rem 1rem;
            gap: 0.5rem;
        }

        .zoom-btn {
            width: 40px;
            height: 40px;
        }
    }

    /* Fadeout animation for alerts */
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

    .legend-color.range {
        background: linear-gradient(135deg, #fee2e2, #fca5a5);
        border-color: #fca5a5;
    }

    .calendar-day.in-range {
        background: linear-gradient(135deg, #fee2e2, #fca5a5);
        color: #991b1b;
        border-color: #fca5a5;
    }

    .selected-date-display {
        margin-bottom: 1.5rem;
        animation: slideDown 0.3s ease;
    }

    .date-display-card {
        background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        padding: 1.5rem;
        border-radius: 12px;
        display: flex;
        gap: 1.25rem;
        align-items: center;
        border: 2px solid var(--primary);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.1);
    }

    .date-display-row {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .calendar-instruction {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border-radius: 10px;
        margin-bottom: 1.5rem;
        color: #92400e;
        font-weight: 500;
    }
</style>

<script>
    // ================= GLOBAL VARIABLES =================
    let currentZoom = 1;
    let zoomModal = null;
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();
    let selectedTeknisiId = null;
    let teknisiSchedule = [];
    let startDate = null;
    let endDate = null;
        
    const monthNames = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    
    const dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

    // ================= IMAGE ZOOM FUNCTIONS =================
    function openImageZoom(imageSrc) {
        zoomModal = document.getElementById('imageZoomModal');
        const img = document.getElementById('zoomedImage');
        img.src = imageSrc;
        currentZoom = 1;
        img.style.transform = `scale(${currentZoom})`;
        zoomModal.showModal();
        
        const handleBackdropClick = function(e) {
            if (e.target === zoomModal) {
                closeZoomModal();
            }
        };
        
        zoomModal.addEventListener('click', handleBackdropClick);
        
        const handleEscKey = function(e) {
            if (e.key === 'Escape') {
                closeZoomModal();
            }
        };
        
        zoomModal.addEventListener('keydown', handleEscKey);
        
        zoomModal.addEventListener('close', function cleanup() {
            zoomModal.removeEventListener('click', handleBackdropClick);
            zoomModal.removeEventListener('keydown', handleEscKey);
            zoomModal.removeEventListener('close', cleanup);
            currentZoom = 1;
            img.style.transform = 'scale(1)';
        }, { once: true });
    }
    
    function closeZoomModal() {
        if (zoomModal) {
            zoomModal.close();
        }
    }

    function zoomIn() {
        currentZoom = Math.min(currentZoom + 0.25, 3);
        document.getElementById('zoomedImage').style.transform = `scale(${currentZoom})`;
    }

    function zoomOut() {
        currentZoom = Math.max(currentZoom - 0.25, 0.5);
        document.getElementById('zoomedImage').style.transform = `scale(${currentZoom})`;
    }

    function resetZoom() {
        currentZoom = 1;
        document.getElementById('zoomedImage').style.transform = `scale(${currentZoom})`;
    }

    // ================= SEARCH AND FILTER =================
    document.getElementById('searchInput').addEventListener('input', function(e) {
        filterTable();
    });

    document.getElementById('statusFilter').addEventListener('change', function() {
        filterTable();
    });

    document.getElementById('keberangkatanFilter').addEventListener('change', function() {
        filterTable();
    });

    function filterTable() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const keberangkatanFilter = document.getElementById('keberangkatanFilter').value;
        const rows = document.querySelectorAll('.laporan-row');

        rows.forEach(row => {
            const bus = row.querySelector('.bus-info strong').textContent.toLowerCase();
            const pelapor = row.querySelector('.pelapor-info').textContent.toLowerCase();
            const status = row.querySelector('.status-badge').textContent.trim();
            const keberangkatan = row.querySelector('.status-keberangkatan').textContent.trim();

            const matchesSearch = bus.includes(searchTerm) || pelapor.includes(searchTerm);
            const matchesStatus = !statusFilter || status === statusFilter;
            const matchesKeberangkatan = !keberangkatanFilter || keberangkatan === keberangkatanFilter;

            if (matchesSearch && matchesStatus && matchesKeberangkatan) {
                row.style.display = '';
                row.style.animation = 'fadeIn 0.3s ease';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // ================= DETAIL VIEW FUNCTION =================
    function lihatDetail(id) {
        const dialog = document.getElementById('dialogDetail');
        const content = document.getElementById('detailContent');
        
        content.innerHTML = `
            <div class="loading-spinner">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="2" x2="12" y2="6"></line>
                    <line x1="12" y1="18" x2="12" y2="22"></line>
                    <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
                    <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
                    <line x1="2" y1="12" x2="6" y2="12"></line>
                    <line x1="18" y1="12" x2="22" y2="12"></line>
                    <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
                    <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
                </svg>
                <p>Memuat data...</p>
            </div>
        `;
        
        dialog.showModal();

        fetch(`/laporan-kerusakan/${id}`)
            .then(res => res.json())
            .then(data => {
                let statusClass = 'status-dilaporkan';
                let statusIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>`;
                
                if (data.status_proses === 'Validasi Diproses') {
                    statusClass = 'status-validasi';
                    statusIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>`;
                } else if (data.status_proses === 'Selesai') {
                    statusClass = 'status-selesai';
                    statusIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>`;
                }

                let keberangkatanClass = 'status-pool';
                if (data.status_keberangkatan === 'Akan Berangkat') {
                    keberangkatanClass = 'status-akan-berangkat';
                } else if (data.status_keberangkatan === 'Perjalanan') {
                    keberangkatanClass = 'status-perjalanan';
                }

                let tingkatClass = 'tingkat-rendah';
                if (data.tingkat?.nama_tingkat === 'Sedang') {
                    tingkatClass = 'tingkat-sedang';
                } else if (data.tingkat?.nama_tingkat === 'Tinggi' || data.tingkat?.nama_tingkat === 'Kritis') {
                    tingkatClass = 'tingkat-tinggi';
                }

                content.innerHTML = `
                    <div class="detail-header-card">
                        <div class="detail-id-section">
                            <div class="detail-id-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                    <line x1="12" y1="9" x2="12" y2="13"></line>
                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                </svg>
                            </div>
                            <div>
                                <span class="detail-label">ID Laporan</span>
                                <h3 class="detail-id">#${data.id_laporan}</h3>
                            </div>
                        </div>
                        <div class="detail-status-badge ${statusClass}">
                            ${statusIcon}
                            ${data.status_proses}
                        </div>
                    </div>

                    <div class="detail-grid">
                        <div class="detail-card highlight-card">
                            <div class="detail-card-icon bus-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M8 6v6M16 6v6M3 16h18M3 10h18M5 20h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="detail-label">Nama Bus</span>
                                <p class="detail-value">${data.bus?.nama_bus ?? '-'}</p>
                            </div>
                        </div>

                        <div class="detail-card highlight-card">
                            <div class="detail-card-icon pelapor-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </div>
                            <div>
                                <span class="detail-label">Pelapor</span>
                                <p class="detail-value">${data.pelapor?.name ?? '-'}</p>
                            </div>
                        </div>

                        <div class="detail-card highlight-card">
                            <div class="detail-card-icon date-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                            </div>
                            <div>
                                <span class="detail-label">Tanggal Lapor</span>
                                <p class="detail-value">${data.tanggal_lapor ?? '-'}</p>
                            </div>
                        </div>

                        <div class="detail-card highlight-card">
                            <div class="detail-card-icon category-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="8" y1="6" x2="21" y2="6"></line>
                                    <line x1="8" y1="12" x2="21" y2="12"></line>
                                    <line x1="8" y1="18" x2="21" y2="18"></line>
                                    <line x1="3" y1="6" x2="3.01" y2="6"></line>
                                    <line x1="3" y1="12" x2="3.01" y2="12"></line>
                                    <line x1="3" y1="18" x2="3.01" y2="18"></line>
                                </svg>
                            </div>
                            <div>
                                <span class="detail-label">Kategori</span>
                                <p class="detail-value">${data.kategori?.nama_kategori ?? '-'}</p>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <div class="section-header">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            <h4>Status Keberangkatan</h4>
                        </div>
                        <div class="tingkat-badge ${keberangkatanClass}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M8 6v6M16 6v6M3 16h18M3 10h18M5 20h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z"></path>
                            </svg>
                            ${data.status_keberangkatan ?? '-'}
                        </div>
                    </div>

                    <div class="detail-section">
                        <div class="section-header">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                <line x1="12" y1="9" x2="12" y2="13"></line>
                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                            </svg>
                            <h4>Tingkat Kerusakan</h4>
                        </div>
                        <div class="tingkat-badge ${tingkatClass}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                            ${data.tingkat?.nama_tingkat ?? '-'}
                        </div>
                    </div>

                    <div class="detail-section">
                        <div class="section-header">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                            <h4>Keterangan Kerusakan</h4>
                        </div>
                        <div class="detail-text-box">
                            <p>${data.keterangan ?? '-'}</p>
                        </div>
                    </div>

                    ${data.foto ? `
                        <div class="detail-section">
                            <div class="section-header">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                    <polyline points="21 15 16 10 5 21"></polyline>
                                </svg>
                                <h4>Foto Kerusakan</h4>
                            </div>
                            <div class="detail-image-wrapper">
                                <div class="detail-image" onclick="openImageZoom('/storage/${data.foto}')">
                                    <img src="/storage/${data.foto}" alt="Foto Kerusakan">
                                </div>
                                <div class="zoom-hint">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                        <line x1="11" y1="8" x2="11" y2="14"></line>
                                        <line x1="8" y1="11" x2="14" y2="11"></line>
                                    </svg>
                                    Klik gambar untuk zoom
                                </div>
                            </div>
                        </div>
                    ` : ''}
                `;
            })
            .catch(error => {
                content.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="15" y1="9" x2="9" y2="15"></line>
                                <line x1="9" y1="9" x2="15" y2="15"></line>
                            </svg>
                        </div>
                        <p>Gagal memuat data. Silakan coba lagi.</p>
                    </div>
                `;
                console.error('Error:', error);
            });
    }

    // ================= PRIORITY MAPPING =================
    function mapPrioritasById(idTingkat) {
        switch (parseInt(idTingkat)) {
            case 1: return 'Rendah';
            case 2: return 'Sedang';
            case 3: return 'Tinggi';
            default: return 'Rendah';
        }
    }

    // ================= CALENDAR FUNCTIONS =================
    function prevMonth() {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        renderCalendar();
    }

    function nextMonth() {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        renderCalendar();
    }

    function renderCalendar() {
        const calendarGrid = document.getElementById('calendarGrid');
        calendarGrid.innerHTML = '';

        document.getElementById('calendarMonthYear').textContent =
            `${monthNames[currentMonth]} ${currentYear}`;

        // Header hari
        dayNames.forEach(day => {
            const header = document.createElement('div');
            header.className = 'calendar-day-header';
            header.textContent = day;
            calendarGrid.appendChild(header);
        });

        const firstDay = new Date(currentYear, currentMonth, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
        const today = formatDate(new Date());

        // Empty sebelum tanggal 1
        for (let i = 0; i < firstDay; i++) {
            const empty = document.createElement('div');
            empty.className = 'calendar-day empty';
            calendarGrid.appendChild(empty);
        }

        // Render tanggal
        for (let day = 1; day <= daysInMonth; day++) {
            const dateObj = new Date(currentYear, currentMonth, day);
            const dateString = formatDate(dateObj);

            const dayCell = document.createElement('div');
            dayCell.classList.add('calendar-day');

            // Tanggal lampau
            if (dateString < today) {
                dayCell.classList.add('past');
                dayCell.textContent = day;
                calendarGrid.appendChild(dayCell);
                continue;
            }

            // Cek sibuk
            if (isDayBusy(dateString)) {
                dayCell.classList.add('busy');
            } else {
                dayCell.classList.add('available');
                dayCell.addEventListener('click', () => selectDate(dateString));
            }

            // Selected & range
            if (dateString === startDate || dateString === endDate) {
                dayCell.classList.add('selected');
            }
            if (startDate && endDate && dateString > startDate && dateString < endDate) {
                dayCell.classList.add('in-range');
            }

            dayCell.textContent = day;
            calendarGrid.appendChild(dayCell);
        }
    }


    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function formatDateDisplay(dateString) {
        // Mengubah "2024-12-16" menjadi "Senin, 16 Des 2024"
        const date = new Date(dateString);
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        
        return `${days[date.getDay()]}, ${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
    }

    function isDateInRange(dateString) {
        if (!startDate || !endDate) return false;
        return dateString > startDate && dateString < endDate;
    }

    function updateDateDisplay() {
        const display = document.getElementById('selectedDateDisplay');
        const startText = document.getElementById('startDateText');
        const endText = document.getElementById('endDateText');
        
        if (startDate) {
            display.style.display = 'block';
            startText.textContent = formatDateDisplay(startDate);
            endText.textContent = endDate ? formatDateDisplay(endDate) : '-';
        } else {
            display.style.display = 'none';
        }
    }

    function isDayBusy(dateString) {
        return teknisiSchedule.some(s => {
            return dateString >= s.tanggal_mulai_estimasi &&
                dateString <= s.tanggal_selesai_estimasi;
        });
    }


    function updateSubmitButton() {
        document.getElementById('submitBtn').disabled =
            !(startDate && endDate && selectedTeknisiId);
    }

    // ================= LOAD TEKNISI SCHEDULE =================
    function loadTeknisiSchedule(teknisiId) {
        if (!teknisiId) {
            document.getElementById('calendarSection').style.display = 'none';
            selectedTeknisiId = null;
            selectedDate = null;
            teknisiSchedule = [];
            updateSubmitButton();
            return;
        }
        
        selectedTeknisiId = teknisiId;
        
        // Fetch teknisi schedule from backend
        fetch(`/api/teknisi-schedule/${teknisiId}`)
            .then(res => res.json())
            .then(data => {
                teknisiSchedule = data.schedules || [];
                document.getElementById('calendarSection').style.display = 'block';
                
                // Reset current month to today
                const today = new Date();
                currentMonth = today.getMonth();
                currentYear = today.getFullYear();
                
                renderCalendar();
            })
            .catch(error => {
                console.error('Error loading schedule:', error);
                // Show calendar anyway with empty schedule
                teknisiSchedule = [];
                document.getElementById('calendarSection').style.display = 'block';
                renderCalendar();
            });
    }

    // ================= JADWAL MODAL FUNCTIONS =================
    function openJadwalModal(idLaporan) {
        console.log('Open jadwal untuk laporan:', idLaporan);

        const form = document.getElementById('formJadwal');
        const prioritasText = document.getElementById('prioritas_auto');
        const prioritasHidden = document.getElementById('prioritas_hidden');

        form.action = `/laporan-perbaikan`;

        let input = form.querySelector('input[name="id_laporan"]');
        if (!input) {
            input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'id_laporan';
            form.appendChild(input);
        }
        input.value = idLaporan;

        prioritasText.value = 'Memuat...';
        prioritasHidden.value = 'Rendah';

        fetch(`/laporan-kerusakan/${idLaporan}`)
            .then(res => res.json())
            .then(data => {
                const prioritas = mapPrioritasById(data.id_tingkat);
                prioritasText.value = prioritas;
                prioritasHidden.value = prioritas;
                const modal = document.getElementById('dialogJadwal');
                if (!modal.open) modal.showModal();
            })
            .catch(() => {
                prioritasText.value = 'Rendah';
                prioritasHidden.value = 'Rendah';
                const modal = document.getElementById('dialogJadwal');
                if (!modal.open) modal.showModal();    
        });
    }


    function closeJadwalModal() {
        document.getElementById('dialogJadwal').close();
        document.getElementById('formJadwal').reset();

        startDate = null;
        endDate = null;
        selectedTeknisiId = null;
        teknisiSchedule = [];

        document.getElementById('calendarSection').style.display = 'none';
        updateSubmitButton();
    }


    // ================= FILE INPUT HANDLER =================
    document.getElementById('add_foto')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const fileName = document.querySelector('.file-name');
        const preview = document.getElementById('imagePreview');
        
        if (file) {
            fileName.textContent = file.name;
            
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            fileName.textContent = 'Pilih file atau drag & drop';
            preview.style.display = 'none';
            preview.innerHTML = '';
        }
    });

    // ================= TABLE SORTING =================
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

    // ================= AUTO-HIDE ALERTS =================
    window.addEventListener('load', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.animation = 'fadeOut 0.5s ease';
                setTimeout(() => alert.remove(), 500);
            }, 5000);
        });
    });

    function selectDate(dateString) {
        if (!startDate) {
            // Klik pertama: set tanggal mulai
            startDate = dateString;
            endDate = null;
        } else if (!endDate) {
            // Klik kedua: set tanggal selesai
            if (dateString < startDate) {
                // Jika klik tanggal sebelum mulai, swap
                endDate = startDate;
                startDate = dateString;
            } else if (dateString === startDate) {
                // Jika klik tanggal yang sama, deselect
                startDate = null;
                endDate = null;
            } else {
                endDate = dateString;
            }
        } else {
            // Klik ketiga: reset dan mulai baru
            startDate = dateString;
            endDate = null;
        }
        
        // Update hidden inputs
        if (startDate) {
            document.getElementById('tanggal_mulai_estimasi').value = startDate;
        }
        if (endDate) {
            document.getElementById('tanggal_selesai_estimasi').value = endDate;
        }
        
        // Update tampilan
        updateDateDisplay();
        
        // Re-render calendar untuk menampilkan selection
        renderCalendar();
        
        // Update submit button
        updateSubmitButton();
    }

</script>

@endsection
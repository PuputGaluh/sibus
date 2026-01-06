@extends('layouts.app')

@section('title', 'Laporan Perbaikan')

@section('content')
<div class="page-container">
    <!-- Header Section -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <div class="icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                    </svg>
                </div>
                <div>
                    <h1>Laporan Perbaikan</h1>
                    <p class="subtitle">Kelola dan monitor proses perbaikan bus</p>
                </div>
            </div>
            @if(auth()->user()->role === 'Dispatcher')
                <button class="btn-add" onclick="document.getElementById('dialogTambahPerbaikan').showModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Tambah Laporan Perbaikan
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
            <div class="stat-icon pending">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </div>
            <div class="stat-content">
                <h3>{{ $countMenunggu }}</h3>
                <p>Pending</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon progress">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
            </div>
            <div class="stat-content">
                <h3>{{ $countDalamProses }}</h3>
                <p>In Progress</p>
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
                <h3>{{ $countSelesai }}</h3>
                <p>Selesai</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon total">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                </svg>
            </div>
            <div class="stat-content">
                <h3>{{ $perbaikan->count() }}</h3>
                <p>Total Perbaikan</p>
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
            <input type="text" id="searchInput" placeholder="Cari berdasarkan nama bus atau teknisi...">
        </div>
        <select id="statusFilter" class="filter-select">
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
                            <span>Prioritas</span>
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
                    <th>
                        <div class="th-content">
                            <span>Teknisi</span>
                            <svg class="sort-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 5v14M5 12l7 7 7-7"/>
                            </svg>
                        </div>
                    </th>
                    <th>Tanggal Validasi</th>
                    <th>Status Perbaikan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="perbaikanTableBody">
                @forelse($perbaikan as $p)
                <tr class="perbaikan-row"data-prioritas="{{ $p->prioritas }}">                    
                    <td>
                        <span class="prioritas-badge
                            @if($p->prioritas === 'Tinggi') prioritas-tinggi
                            @elseif($p->prioritas === 'Sedang') prioritas-sedang
                            @else prioritas-rendah
                            @endif">
                            {{ $p->prioritas }}
                        </span>
                    </td>                    
                    <td>
                        <div class="bus-info">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M8 6v6M16 6v6M3 16h18M3 10h18M5 20h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z"></path>
                            </svg>
                            <strong>{{ $p->laporanKerusakan->bus->nama_bus ?? '-' }}</strong>
                        </div>
                    </td>
                    <td>
                        <div class="teknisi-info">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            {{ $p->teknisi->name ?? '-' }}
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
                            {{ $p->tanggal_validasi }}
                        </div>
                    </td>
                    <td>
                        <span class="status-badge
                            @if($p->status_perbaikan === 'Pending') status-menunggu
                            @elseif($p->status_perbaikan === 'In Progress') status-proses
                            @elseif($p->status_perbaikan === 'Menunggu Validasi') status-proses
                            @else status-selesai
                            @endif">
                            {{ $p->status_perbaikan }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-action btn-view" 
                                    onclick="lihatDetail({{ $p->id_perbaikan }})"
                                    title="Lihat Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>

                            @if(auth()->user()->role === 'Teknisi')
                                @if($p->status_perbaikan === 'Pending')
                                    {{-- BUTTON IN PROGRESS --}}
                                    <form action="{{ route('perbaikan.updateStatus', $p->id_perbaikan) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status_perbaikan" value="In Progress">
                                        <button class="btn-action btn-edit" title="Mulai Perbaikan">
                                            ▶
                                        </button>
                                    </form>
                                @elseif($p->status_perbaikan === 'In Progress')
                                    {{-- BUTTON INPUT HASIL --}}
                                    <button class="btn-action btn-edit"
                                        onclick="document.getElementById('dialogHasil{{ $p->id_perbaikan }}').showModal()"
                                        title="Input Hasil Perbaikan">
                                        📝
                                    </button>
                                @endif
                            @endif

                            @if(auth()->user()->role === 'Dispatcher' && $p->status_perbaikan === 'Menunggu Validasi')
                            <form action="{{ route('perbaikan.setSelesai', $p->id_perbaikan) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button class="btn-action btn-edit" title="Set Selesai">
                                    ✔
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>

                {{-- DIALOG UPDATE STATUS --}}
                @if(auth()->user()->role === 'Teknisi' && $p->status_perbaikan !== 'Selesai')
                <dialog id="dialogUpdateStatus{{ $p->id_perbaikan }}" class="modern-dialog">
                    <form method="POST" action="{{ route('perbaikan.updateStatus', $p->id_perbaikan) }}" class="dialog-form">
                        @csrf
                        @method('PUT')
                        <div class="dialog-header">
                            <div class="dialog-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                                <h5>Update Status Perbaikan</h5>
                            </div>
                            <button type="button" class="btn-close" onclick="document.getElementById('dialogUpdateStatus{{ $p->id_perbaikan }}').close()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </div>
                        <div class="dialog-body">
                            <div class="form-group">
                                <label for="status_{{ $p->id_perbaikan }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                                    </svg>
                                    Status Perbaikan
                                </label>
                                <select id="status_{{ $p->id_perbaikan }}" class="form-input" name="status_perbaikan" required>
                                    <option value="Pending" {{ $p->status_perbaikan == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="In Progress" {{ $p->status_perbaikan == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="Selesai">Selesai</option>
                                </select>
                            </div>
                        </div>
                        <div class="dialog-footer">
                            <button type="button" class="btn-secondary" onclick="document.getElementById('dialogUpdateStatus{{ $p->id_perbaikan }}').close()">
                                Batal
                            </button>
                            <button type="submit" class="btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                Update Status
                            </button>
                        </div>
                    </form>
                </dialog>
                @endif

                @empty
                <tr>
                    <td colspan="6" class="empty-state">
                        <div class="empty-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                            </svg>
                        </div>
                        <p>Data perbaikan belum tersedia</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ================= IMAGE ZOOM MODAL ================= --}}
<dialog id="imageZoomModal" class="image-zoom-modal">
    <div class="zoom-container">
        <button type="button" class="close-zoom" onclick="closeZoomModal()">✕</button>
        <div class="zoom-controls">
            <button type="button" class="zoom-btn" onclick="zoomInImage()">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="11" y1="8" x2="11" y2="14"></line>
                    <line x1="8" y1="11" x2="14" y2="11"></line>
                </svg>
            </button>
            <button type="button" class="zoom-btn" onclick="zoomOutImage()">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="8" y1="11" x2="14" y2="11"></line>
                </svg>
            </button>
            <button type="button" class="zoom-btn" onclick="resetZoom()">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M1 4v6h6"></path>
                    <path d="M23 20v-6h-6"></path>
                    <path d="M20.49 9A9 9 0 0 0 5.64 5.64"></path>
                    <path d="M3.51 15A9 9 0 0 0 18.36 18.36"></path>
                </svg>
            </button>
        </div>
        <img id="zoomedImage" src="" alt="Zoomed Image" class="zoomed-image" ondragstart="return false">
    </div>
</dialog>

{{-- ================= DIALOG HASIL PERBAIKAN ================= --}}
@foreach($perbaikan as $p)
@if(auth()->user()->role === 'Teknisi' && $p->status_perbaikan === 'In Progress')
<dialog id="dialogHasil{{ $p->id_perbaikan }}" class="modern-dialog">
    <form method="POST" action="{{ route('perbaikan.submitHasil', $p->id_perbaikan) }}" class="dialog-form" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="dialog-header">
            <h5>Hasil Perbaikan</h5>
            <button type="button" class="btn-close"
                onclick="document.getElementById('dialogHasil{{ $p->id_perbaikan }}').close()">✕</button>
        </div>

        <div class="dialog-body">
            <div class="form-group">
                <label>Deskripsi Perbaikan</label>
                <textarea name="deskripsi_pekerjaan_teknisi"
                    class="form-input" required></textarea>
            </div>

            <div class="form-group">
                <label for="gambar_perbaikan_{{ $p->id_perbaikan }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                    Bukti Foto Perbaikan
                </label>
                <div class="file-upload-wrapper">
                    <input type="file" id="gambar_perbaikan_{{ $p->id_perbaikan }}" name="gambar_perbaikan" 
                           class="file-input" accept="image/*" onchange="previewImage(this, 'preview_{{ $p->id_perbaikan }}')">
                    <label for="gambar_perbaikan_{{ $p->id_perbaikan }}" class="file-upload-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                        <div>
                            <p class="upload-title">Klik atau drag gambar ke sini</p>
                            <p class="upload-subtitle">PNG, JPG, GIF (max. 5MB)</p>
                        </div>
                    </label>
                </div>
                <div id="preview_{{ $p->id_perbaikan }}" class="image-preview" style="display: none;">
                    <img src="" alt="Preview" id="preview_img_{{ $p->id_perbaikan }}">
                    <button type="button" class="btn-remove-image" onclick="removeImage('gambar_perbaikan_{{ $p->id_perbaikan }}', 'preview_{{ $p->id_perbaikan }}')">✕</button>
                </div>
            </div>
        </div>

        <div class="dialog-footer">
            <button type="submit" class="btn-primary">Kirim Hasil</button>
        </div>
    </form>
</dialog>
@endif
@endforeach


{{-- ================= DIALOG TAMBAH PERBAIKAN ================= --}}
<dialog id="dialogTambahPerbaikan" class="modern-dialog">
    <form action="{{ route('laporan-perbaikan.store') }}" method="POST" class="dialog-form">
        @csrf
        <div class="dialog-header">
            <div class="dialog-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                </svg>
                <h5>Form Laporan Perbaikan</h5>
            </div>
            <button type="button" class="btn-close" onclick="document.getElementById('dialogTambahPerbaikan').close()">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div class="dialog-body">
            <div class="form-group">
                <label for="add_laporan">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                    Laporan Kerusakan
                </label>
                <select id="add_laporan" class="form-input" name="id_laporan" required>
                    <option value="">Pilih Laporan Kerusakan</option>
                    @foreach($laporanKerusakan as $lk)
                        <option value="{{ $lk->id_laporan }}">
                            #{{ $lk->id_laporan }} - {{ $lk->bus->nama_bus ?? '-' }} ({{ $lk->status_proses }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="add_teknisi">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Teknisi
                </label>
                <select id="add_teknisi" class="form-input" name="id_teknisi" required>
                    <option value="">Pilih Teknisi</option>
                    @foreach($teknisi as $t)
                        <option value="{{ $t->id_user }}">{{ $t->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="add_tanggal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    Tanggal Validasi
                </label>
                <input type="date" id="add_tanggal" class="form-input" name="tanggal_validasi" required>
            </div>
        </div>
        <div class="dialog-footer">
            <button type="button" class="btn-secondary" onclick="document.getElementById('dialogTambahPerbaikan').close()">
                Batal
            </button>
            <button type="submit" class="btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                Tambah Perbaikan
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
            <h5>Detail Laporan Perbaikan</h5>
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

    .stat-icon.pending {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .stat-icon.progress {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    }

    .stat-icon.completed {
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

    .bus-info, .teknisi-info, .date-cell {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--dark);
    }

    .bus-info svg, .teknisi-info svg, .date-cell svg {
        color: var(--secondary);
    }

    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .status-menunggu {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    .status-proses {
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

    .btn-edit {
        background: #fef3c7;
        color: #f59e0b;
    }

    .btn-edit:hover {
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

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
    }

    .btn-secondary {
        background: #e2e8f0;
        color: var(--secondary);
    }

    .btn-secondary:hover {
        background: #cbd5e1;
    }

    /* Enhanced Detail Styles */
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

    .teknisi-icon {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    }

    .date-icon {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .category-icon {
        background: linear-gradient(135deg, #f59e0b, #d97706);
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

    .prioritas-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
    }

    .prioritas-rendah {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .prioritas-sedang {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    .prioritas-tinggi {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .prioritas-mendesak {
        background: linear-gradient(135deg, #7c2d12, #991b1b);
        color: white;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% {
            box-shadow: 0 0 0 0 rgba(124, 45, 18, 0.7);
        }
        50% {
            box-shadow: 0 0 0 10px rgba(124, 45, 18, 0);
        }
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

    .empty-box {
        background: #fff7ed;
        border-color: #fed7aa;
    }

    .empty-content {
        text-align: center;
        padding: 2rem;
    }

    .empty-content svg {
        color: #f59e0b;
        margin-bottom: 0.75rem;
    }

    .empty-content p {
        color: #92400e;
        font-style: italic;
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

    /* File Upload Styles */
    .file-input {
        display: none;
    }

    .file-upload-wrapper {
        position: relative;
    }

    .file-upload-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        border: 2px dashed var(--border);
        border-radius: 10px;
        background: linear-gradient(135deg, rgba(220, 38, 38, 0.05), rgba(153, 27, 27, 0.05));
        cursor: pointer;
        transition: all 0.3s ease;
        min-height: 150px;
    }

    .file-upload-label:hover {
        border-color: var(--primary);
        background: linear-gradient(135deg, rgba(220, 38, 38, 0.1), rgba(153, 27, 27, 0.1));
    }

    .file-upload-label svg {
        width: 40px;
        height: 40px;
        color: var(--primary);
        margin-bottom: 0.75rem;
    }

    .upload-title {
        font-weight: 600;
        color: var(--dark);
        margin: 0;
    }

    .upload-subtitle {
        font-size: 0.85rem;
        color: var(--secondary);
        margin: 0.25rem 0 0 0;
    }

    .image-preview {
        position: relative;
        margin-top: 1rem;
        border-radius: 10px;
        overflow: hidden;
        border: 2px solid var(--border);
    }

    .image-preview img {
        width: 100%;
        height: auto;
        max-height: 300px;
        object-fit: cover;
        display: block;
    }

    .btn-remove-image {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 32px;
        height: 32px;
        background: rgba(220, 38, 38, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        transition: all 0.3s ease;
    }

    .btn-remove-image:hover {
        background: rgba(220, 38, 38, 1);
        transform: scale(1.1);
    }

    /* Image Gallery in Detail */
    .image-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .gallery-item {
        position: relative;
        border-radius: 10px;
        overflow: hidden;
        border: 2px solid var(--border);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .gallery-item:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .gallery-item img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        display: block;
    }

    /* Detail Image Styles */
    .detail-image-wrapper {
        position: relative;
        margin-top: 1rem;
    }

    .detail-image {
        position: relative;
        border-radius: 10px;
        overflow: hidden;
        border: 2px solid var(--border);
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-block;
        width: 100%;
    }

    .detail-image:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        transform: scale(1.02);
    }

    .detail-image img {
        width: 100%;
        height: auto;
        max-height: 500px;
        object-fit: cover;
        display: block;
    }

    .zoom-hint {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.75rem;
        color: var(--secondary);
        font-size: 0.85rem;
    }

    /* Image Zoom Modal */
    .image-zoom-modal {
        border: none;
        border-radius: 0;
        padding: 0;
        width: 100%;
        height: 100%;
        max-width: none;
        max-height: none;
        background: rgba(0, 0, 0, 0.95);
        display: none;
        align-items: center;
        justify-content: center;
        position: fixed;
        top: 0;
        left: 0;
    }

    .image-zoom-modal[open] {
        display: flex;
    }

    .image-zoom-modal::backdrop {
        background: rgba(0, 0, 0, 0.95);
    }

    .zoom-container {
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: auto;
    }

    .close-zoom {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 44px;
        height: 44px;
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid white;
        color: white;
        font-size: 28px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        z-index: 10;
    }

    .close-zoom:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
    }

    .zoom-controls {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 0.75rem;
        z-index: 10;
        background: rgba(0, 0, 0, 0.6);
        padding: 0.75rem;
        border-radius: 50px;
        backdrop-filter: blur(10px);
    }

    .zoom-btn {
        width: 44px;
        height: 44px;
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid white;
        color: white;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .zoom-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
    }

    .zoomed-image {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
        transition: transform 0.3s ease;
        user-select: none;
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

        .detail-grid {
            grid-template-columns: 1fr;
        }

        .detail-header-card {
            flex-direction: column;
            gap: 1rem;
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
        const rows = document.querySelectorAll('.perbaikan-row');

        rows.forEach(row => {
            const bus = row.querySelector('.bus-info strong').textContent.toLowerCase();
            const teknisi = row.querySelector('.teknisi-info').textContent.toLowerCase();
            const status = row.querySelector('.status-badge').textContent.trim();

            const matchesSearch = bus.includes(searchTerm) || teknisi.includes(searchTerm);
            const matchesStatus = !statusFilter || status === statusFilter;

            if (matchesSearch && matchesStatus) {
                row.style.display = '';
                row.style.animation = 'fadeIn 0.3s ease';
            } else {
                row.style.display = 'none';
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const tbody = document.getElementById('perbaikanTableBody');
        const rows = Array.from(tbody.querySelectorAll('.perbaikan-row'));

        const urutanPrioritas = {
            'Tinggi': 1,
            'Sedang': 2,
            'Rendah': 3
        };

        rows.sort((a, b) => {
            const pa = a.dataset.prioritas || 'Rendah';
            const pb = b.dataset.prioritas || 'Rendah';
            return urutanPrioritas[pa] - urutanPrioritas[pb];
        });

        rows.forEach(row => tbody.appendChild(row));
    });

    // View detail function (ENHANCED)
    function lihatDetail(id) {
        const dialog = document.getElementById('dialogDetail');
        const content = document.getElementById('detailContent');
        
        // Show loading
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

        fetch(`/laporan-perbaikan/${id}`)
            .then(res => res.json())
            .then(data => {
                // Determine status color and icon
                let statusClass = 'status-menunggu';
                let statusIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>`;
                
                if (data.status_perbaikan === 'In Progress') {
                    statusClass = 'status-proses';
                    statusIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>`;
                } else if (data.status_perbaikan === 'Selesai') {
                    statusClass = 'status-selesai';
                    statusIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>`;
                } else if (data.status_perbaikan === 'Menunggu Validasi') {
                    statusClass = 'status-proses';
                    statusIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>`;
                }

                // Determine tingkat kerusakan color
                let tingkatClass = 'tingkat-rendah';
                if (data.laporan_kerusakan?.tingkat?.nama_tingkat === 'Sedang') {
                    tingkatClass = 'tingkat-sedang';
                } else if (data.laporan_kerusakan?.tingkat?.nama_tingkat === 'Tinggi' || data.laporan_kerusakan?.tingkat?.nama_tingkat === 'Kritis') {
                    tingkatClass = 'tingkat-tinggi';
                }

                // Determine prioritas color
                let prioritasClass = 'prioritas-rendah';
                if (data.prioritas === 'Sedang') {
                    prioritasClass = 'prioritas-sedang';
                } else if (data.prioritas === 'Tinggi') {
                    prioritasClass = 'prioritas-tinggi';
                } else if (data.prioritas === 'Mendesak') {
                    prioritasClass = 'prioritas-mendesak';
                }

                content.innerHTML = `
                    <!-- Header Card -->
                    <div class="detail-header-card">
                        <div class="detail-id-section">
                            <div class="detail-id-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="detail-label">ID Perbaikan</span>
                                <h3 class="detail-id">#${data.id_perbaikan}</h3>
                            </div>
                        </div>
                        <div class="detail-status-badge ${statusClass}">
                            ${statusIcon}
                            ${data.status_perbaikan}
                        </div>
                    </div>

                    <!-- Main Info Grid -->
                    <div class="detail-grid">
                        <!-- Bus Info -->
                        <div class="detail-card highlight-card">
                            <div class="detail-card-icon bus-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M8 6v6M16 6v6M3 16h18M3 10h18M5 20h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="detail-label">Bus</span>
                                <p class="detail-value">${data.laporan_kerusakan?.bus?.nama_bus ?? '-'}</p>
                            </div>
                        </div>

                        <!-- Teknisi Info -->
                        <div class="detail-card highlight-card">
                            <div class="detail-card-icon teknisi-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </div>
                            <div>
                                <span class="detail-label">Teknisi</span>
                                <p class="detail-value">${data.teknisi?.name ?? '-'}</p>
                            </div>
                        </div>

                        <!-- Tanggal Info -->
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
                                <span class="detail-label">Tanggal Validasi</span>
                                <p class="detail-value">${data.tanggal_validasi ?? '-'}</p>
                            </div>
                        </div>

                        <!-- Kategori Info -->
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
                                <p class="detail-value">${data.laporan_kerusakan?.kategori?.nama_kategori ?? '-'}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Jadwal Perbaikan Section -->
                    <div class="detail-section">
                        <div class="section-header">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            <h4>Jadwal Perbaikan</h4>
                        </div>
                        
                        <!-- Grid untuk Tanggal Mulai & Selesai Estimasi -->
                        <div class="detail-grid">
                            <!-- Tanggal Mulai Estimasi -->
                            <div class="detail-card highlight-card">
                                <div class="detail-card-icon" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="9 11 12 14 22 4"></polyline>
                                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="detail-label">Tanggal Mulai Estimasi</span>
                                    <p class="detail-value">${data.tanggal_mulai_estimasi ? new Date(data.tanggal_mulai_estimasi).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'}) : '-'}</p>
                                </div>
                            </div>
                            
                            <!-- Tanggal Selesai Estimasi -->
                            <div class="detail-card highlight-card">
                                <div class="detail-card-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </div>
                                <div>
                                    <span class="detail-label">Tanggal Selesai Estimasi</span>
                                    <p class="detail-value">${data.tanggal_selesai_estimasi ? new Date(data.tanggal_selesai_estimasi).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'}) : '-'}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tanggal Selesai Aktual (jika ada) -->
                        ${data.tanggal_selesai_aktual ? `
                            <div style="margin-top: 1rem;">
                                <div class="detail-card" style="border-left: 4px solid #10b981; background: linear-gradient(135deg, #d1fae5, #ecfdf5);">
                                    <div class="detail-card-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="detail-label">Tanggal Selesai Aktual</span>
                                        <p class="detail-value" style="color: #059669; font-size: 1.1rem;">${new Date(data.tanggal_selesai_aktual).toLocaleDateString('id-ID', {
                                            weekday: 'long',
                                            day: 'numeric', 
                                            month: 'long', 
                                            year: 'numeric', 
                                            hour: '2-digit', 
                                            minute: '2-digit'
                                        })}</p>
                                    </div>
                                </div>
                            </div>
                        ` : ''}
                        
                        <!-- Grid untuk Durasi -->
                        ${data.tanggal_mulai_estimasi && data.tanggal_selesai_estimasi ? `
                            <div style="margin-top: 1rem;">
                                <div class="detail-grid">
                                    <!-- Estimasi Durasi -->
                                    <div class="detail-card" style="background: linear-gradient(135deg, #fef3c7, #fde68a); border-left: 4px solid #f59e0b;">
                                        <div class="detail-card-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="detail-label">Estimasi Durasi</span>
                                            <p class="detail-value" style="color: #92400e;">
                                                ${(() => {
                                                    const start = new Date(data.tanggal_mulai_estimasi);
                                                    const end = new Date(data.tanggal_selesai_estimasi);
                                                    const diffTime = Math.abs(end - start);
                                                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                                                    return diffDays === 0 ? 'Kurang dari 1 hari' : diffDays + ' hari';
                                                })()}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <!-- Durasi Aktual (jika sudah selesai) -->
                                    ${data.tanggal_selesai_aktual ? `
                                        <div class="detail-card" style="background: linear-gradient(135deg, #dbeafe, #bfdbfe); border-left: 4px solid #3b82f6;">
                                            <div class="detail-card-icon" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <polyline points="12 6 12 12 16 14"></polyline>
                                                </svg>
                                            </div>
                                            <div>
                                                <span class="detail-label">Durasi Aktual</span>
                                                <p class="detail-value" style="color: #1e40af;">
                                                    ${(() => {
                                                        const estimatedStart = new Date(data.tanggal_mulai_estimasi);
                                                        const estimatedEnd = new Date(data.tanggal_selesai_estimasi);
                                                        const actualEnd = new Date(data.tanggal_selesai_aktual);
                                                        
                                                        // Hitung durasi estimasi dalam hari (gunakan floor untuk konsistensi)
                                                        const estimatedDays = Math.floor((estimatedEnd - estimatedStart) / (1000 * 60 * 60 * 24));
                                                        
                                                        // Hitung durasi aktual dalam hari (gunakan floor untuk konsistensi)
                                                        const actualDays = Math.floor((actualEnd - estimatedStart) / (1000 * 60 * 60 * 24));
                                                        
                                                        // Tentukan status: apakah tepat waktu atau terlambat
                                                        // Tepat waktu jika durasi aktual <= durasi estimasi
                                                        const status = actualDays <= estimatedDays ? '✓ Tepat Waktu' : '⚠ Terlambat';
                                                        const statusColor = actualDays <= estimatedDays ? '#059669' : '#dc2626';
                                                        
                                                        const durationText = actualDays === 0 ? 'Kurang dari 1 hari' : actualDays + ' hari';
                                                        
                                                        return durationText + ' <span style="color: ' + statusColor + '; font-weight: 600;">' + status + '</span>';
                                                    })()}
                                                </p>
                                            </div>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
                        ` : ''}
                    </div>

                    <!-- Tingkat Kerusakan -->
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
                            ${data.laporan_kerusakan?.tingkat?.nama_tingkat ?? '-'}
                        </div>
                    </div>

                    <!-- Keterangan Kerusakan -->
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
                            <p>${data.laporan_kerusakan?.keterangan ?? '-'}</p>
                        </div>
                    </div>

                    <!-- Prioritas Perbaikan -->
                    <div class="detail-section">
                        <div class="section-header">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                            <h4>Prioritas Perbaikan</h4>
                        </div>
                        <div class="prioritas-badge ${prioritasClass}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M12 6v6l4 2"></path>
                            </svg>
                            ${data.prioritas ?? '-'}
                        </div>
                    </div>

                    <!-- Hasil Perbaikan -->
                    <div class="detail-section">
                        <div class="section-header">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                            <h4>Deskripsi Pekerjaan Teknisi</h4>
                        </div>
                        <div class="detail-text-box ${!data.deskripsi_pekerjaan_teknisi ? 'empty-box' : ''}">
                            ${data.deskripsi_pekerjaan_teknisi 
                                ? `<p>${data.deskripsi_pekerjaan_teknisi}</p>` 
                                : `
                                <div class="empty-content">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                    </svg>
                                    <p>Hasil perbaikan belum diinput oleh teknisi</p>
                                </div>
                                `
                            }
                        </div>
                    </div>

                    <!-- Bukti Foto Perbaikan -->
                    ${data.gambar_perbaikan ? `
                    <div class="detail-section">
                        <div class="section-header">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21 15 16 10 5 21"></polyline>
                            </svg>
                            <h4>Bukti Foto Perbaikan</h4>
                        </div>
                        <div class="detail-image-wrapper">
                            <div class="detail-image" onclick="openImageZoom('/storage/perbaikan/' + data.gambar_perbaikan)">
                                <img src="/storage/perbaikan/${data.gambar_perbaikan}" alt="Bukti Perbaikan">
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

    // Preview image function
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        const file = input.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImg = preview.querySelector('img');
                previewImg.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    }

    // Remove image function
    function removeImage(inputId, previewId) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        input.value = '';
        preview.style.display = 'none';
    }

    // Image Zoom Functions
    let currentZoom = 1;
    let zoomModal = null;

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
        });
    }

    function closeZoomModal() {
        if (zoomModal) {
            zoomModal.close();
        }
    }

    function zoomInImage() {
        currentZoom += 0.2;
        const img = document.getElementById('zoomedImage');
        img.style.transform = `scale(${currentZoom})`;
    }

    function zoomOutImage() {
        if (currentZoom > 1) {
            currentZoom -= 0.2;
            const img = document.getElementById('zoomedImage');
            img.style.transform = `scale(${currentZoom})`;
        }
    }

    function resetZoom() {
        currentZoom = 1;
        const img = document.getElementById('zoomedImage');
        img.style.transform = `scale(${currentZoom})`;
    }

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
</script>
@endsection
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

{{-- ================= DIALOG DETAIL ================= --}}
<dialog id="dialogDetail" class="modern-dialog">
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

{{-- ================= DIALOG JADWALKAN PERBAIKAN ================= --}}
<dialog id="dialogJadwal" class="modern-dialog">
    <form method="POST" id="formJadwal" class="dialog-form">
        @csrf
        @method('PUT')

        <div class="dialog-header">
            <div class="dialog-title">
                <h5>Jadwalkan Perbaikan</h5>
            </div>
            <button type="button" class="btn-close"
                onclick="document.getElementById('dialogJadwal').close()">✕</button>
        </div>

        <div class="dialog-body">

            <div class="form-group">
                <label>Teknisi</label>
                <select name="id_teknisi" id="id_teknisi" class="form-input" required>
                    <option value="">Pilih Teknisi</option>
                    @foreach($teknisi as $t)
                        <option value="{{ $t->id_user }}">{{ $t->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Tanggal Mulai</label>
                    <input type="datetime-local"
                           name="tanggal_mulai_estimasi"
                           class="form-input" required>
                </div>

                <div class="form-group">
                    <label>Tanggal Selesai</label>
                    <input type="datetime-local"
                           name="tanggal_selesai_estimasi"
                           class="form-input" required>
                </div>
            </div>

            <div class="form-group">
                <label>Prioritas</label>
                <select name="prioritas" class="form-input" required>
                    <option value="Rendah">Rendah</option>
                    <option value="Sedang">Sedang</option>
                    <option value="Tinggi">Tinggi</option>
                    <option value="Mendesak">Mendesak</option>
                </select>
            </div>

            <div class="form-group">
                <label>Catatan Dispatcher</label>
                <textarea name="catatan"
                          class="form-input"
                          placeholder="Catatan untuk teknisi..."></textarea>
            </div>

        </div>

        <div class="dialog-footer">
            <button type="button"
                    class="btn-secondary"
                    onclick="document.getElementById('dialogJadwal').close()">
                Batal
            </button>

            <button type="submit" class="btn-primary">
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
        max-height: 60vh;
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

    /* Detail Content */
    .detail-item {
        padding: 1rem;
        background: #f8fafc;
        border-radius: 10px;
        margin-bottom: 1rem;
    }

    .detail-item strong {
        display: block;
        color: var(--dark);
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .detail-item p {
        color: var(--secondary);
        margin: 0;
    }

    .detail-image {
        margin-top: 1rem;
        border-radius: 10px;
        overflow: hidden;
    }

    .detail-image img {
        width: 100%;
        height: auto;
        display: block;
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

        .form-row {
            grid-template-columns: 1fr;
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

    // View detail function
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

        fetch(`/laporan-kerusakan/${id}`)
            .then(res => res.json())
            .then(data => {
                content.innerHTML = `
                    <div class="detail-item">
                        <strong>Nama Bus</strong>
                        <p>${data.bus?.nama_bus ?? '-'}</p>
                    </div>
                    <div class="detail-item">
                        <strong>Pelapor</strong>
                        <p>${data.pelapor?.name ?? '-'}</p>
                    </div>
                    <div class="detail-item">
                        <strong>Tanggal Lapor</strong>
                        <p>${data.tanggal_lapor ?? '-'}</p>
                    </div>
                    <div class="detail-item">
                        <strong>Status Keberangkatan</strong>
                        <p>${data.status_keberangkatan ?? '-'}</p>
                    </div>
                    <div class="detail-item">
                        <strong>Status Proses</strong>
                        <p>${data.status_proses ?? '-'}</p>
                    </div>
                    <div class="detail-item">
                        <strong>Kategori Kerusakan</strong>
                        <p>${data.kategori?.nama_kategori ?? '-'}</p>
                    </div>
                    <div class="detail-item">
                        <strong>Tingkat Kerusakan</strong>
                        <p>${data.tingkat?.nama_tingkat ?? '-'}</p>
                    </div>
                    <div class="detail-item">
                        <strong>Keterangan</strong>
                        <p>${data.keterangan ?? '-'}</p>
                    </div>
                    ${data.foto ? `
                        <div class="detail-item">
                            <strong>Foto Kerusakan</strong>
                            <div class="detail-image">
                                <img src="/storage/${data.foto}" alt="Foto Kerusakan">
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

    function openJadwalModal(idLaporan) {
        const form = document.getElementById('formJadwal');

        form.action = `/laporan-kerusakan/${idLaporan}/jadwalkan`;

        document.getElementById('dialogJadwal').showModal();
    }


    // File input handler with preview
    document.getElementById('add_foto').addEventListener('change', function(e) {
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
</script>
@endsection
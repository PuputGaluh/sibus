<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rekap Laporan Kerusakan dan Perbaikan</title>
    <style>
        /* Reset & Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.4;
            color: #222;
            padding: 15mm;
        }

        /* Print-specific */
        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        @media print {
            body {
                padding: 0;
            }
            
            .page-break {
                page-break-after: always;
            }
            
            thead {
                display: table-header-group;
            }
            
            tr {
                page-break-inside: avoid;
            }
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #dc2626;
            padding-bottom: 10px;
        }

        .header h1 {
            color: #dc2626;
            font-size: 16pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .header h2 {
            color: #666;
            font-size: 11pt;
            font-weight: normal;
        }

        /* Meta Info */
        .meta-info {
            text-align: right;
            font-size: 8pt;
            color: #666;
            margin-bottom: 10px;
            line-height: 1.6;
        }

        .filter-info {
            background: #f8fafc;
            padding: 8px 10px;
            margin-bottom: 10px;
            border-left: 4px solid #dc2626;
            font-size: 9pt;
        }

        /* Summary Section */
        .summary {
            margin: 15px 0;
            padding: 12px;
            background: #f8fafc;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        .summary h3 {
            font-size: 11pt;
            color: #dc2626;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .summary-grid {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .summary-item {
            display: table-cell;
            text-align: center;
            padding: 8px;
            vertical-align: middle;
        }

        .summary-item:not(:last-child) {
            border-right: 1px solid #ddd;
        }

        .summary-value {
            font-size: 18pt;
            font-weight: bold;
            color: #dc2626;
            display: block;
            margin-bottom: 3px;
        }

        .summary-label {
            font-size: 8pt;
            color: #666;
            display: block;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 8pt;
        }

        thead {
            background-color: #dc2626;
            color: white;
        }

        th {
            padding: 8px 4px;
            font-size: 8pt;
            font-weight: bold;
            text-align: center;
            border: 1px solid #dc2626;
            vertical-align: middle;
        }

        td {
            padding: 6px 4px;
            font-size: 8pt;
            border: 1px solid #ddd;
            vertical-align: top;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        tbody tr:hover {
            background-color: #f1f5f9;
        }

        /* Column Widths */
        .col-no { width: 3%; }
        .col-id { width: 5%; }
        .col-bus { width: 8%; }
        .col-kategori { width: 9%; }
        .col-tingkat { width: 6%; }
        .col-tanggal { width: 8%; }
        .col-lokasi { width: 9%; }
        .col-status { width: 8%; }
        .col-teknisi { width: 8%; }
        .col-kerusakan { width: 13%; }
        .col-perbaikan { width: 13%; }
        .col-selesai { width: 10%; }

        /* Text Alignment */
        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-muted {
            color: #999;
            font-style: italic;
        }

        /* Badges */
        .badge {
            padding: 3px 6px;
            border-radius: 8px;
            font-size: 7pt;
            font-weight: bold;
            display: inline-block;
            white-space: nowrap;
            text-align: center;
        }

        /* Tingkat Badges */
        .badge-ringan {
            background-color: #10b981;
            color: #fff;
        }

        .badge-sedang {
            background-color: #f59e0b;
            color: #fff;
        }

        .badge-berat {
            background-color: #ef4444;
            color: #fff;
        }

        .badge-tinggi {
            background-color: #ef4444;
            color: #fff;
        }

        /* Status Badges */
        .badge-pending {
            background-color: #f59e0b;
            color: #fff;
        }

        .badge-progress {
            background-color: #3b82f6;
            color: #fff;
        }

        .badge-selesai {
            background-color: #10b981;
            color: #fff;
        }

        .badge-default {
            background-color: #64748b;
            color: #fff;
        }

        /* Footer */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #dc2626;
            text-align: center;
            font-size: 8pt;
            color: #666;
            line-height: 1.6;
        }

        /* Utility */
        .font-bold {
            font-weight: bold;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>REKAP LAPORAN KERUSAKAN DAN PERBAIKAN</h1>
        <h2>Sistem Pemeliharaan Bus Listrik</h2>
    </div>

    <!-- Filter Info (jika ada) -->
    <!-- Uncomment jika menggunakan filter
    <div class="filter-info">
        <strong>Periode:</strong> [Filter Period Here]
    </div>
    -->

    <!-- Meta Info -->
    <div class="meta-info">
        <div>Tanggal Cetak: {{ date('d F Y, H:i:s') }}</div>
        <div>Total Data: {{ count($rekap) }} Laporan</div>
    </div>

    <!-- Summary Statistics -->
    <div class="summary">
        <h3>Ringkasan Data</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <span class="summary-value">{{ count($rekap) }}</span>
                <span class="summary-label">Total Laporan</span>
            </div>
            <div class="summary-item">
                <span class="summary-value">{{ $rekap->whereIn('status_perbaikan', ['Belum Dijadwalkan', 'Pending'])->count() }}</span>
                <span class="summary-label">Belum/Pending</span>
            </div>
            <div class="summary-item">
                <span class="summary-value">{{ $rekap->whereIn('status_perbaikan', ['In Progress', 'Menunggu Validasi'])->count() }}</span>
                <span class="summary-label">Dalam Proses</span>
            </div>
            <div class="summary-item">
                <span class="summary-value">{{ $rekap->where('status_perbaikan', 'Selesai')->count() }}</span>
                <span class="summary-label">Selesai</span>
            </div>
        </div>
    </div>

    <!-- Main Table -->
    <table>
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-id">ID</th>
                <th class="col-bus">Bus</th>
                <th class="col-kategori">Kategori</th>
                <th class="col-tingkat">Tingkat</th>
                <th class="col-tanggal">Tgl Lapor</th>
                <th class="col-lokasi">Lokasi</th>
                <th class="col-status">Status</th>
                <th class="col-teknisi">Teknisi</th>
                <th class="col-kerusakan">Catatan Kerusakan</th>
                <th class="col-perbaikan">Catatan Perbaikan</th>
                <th class="col-selesai">Tgl Selesai</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rekap as $index => $row)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">#{{ $row->id_laporan }}</td>
                <td class="font-bold">{{ $row->nama_bus }}</td>
                <td>{{ $row->nama_kategori }}</td>
                <td class="text-center">
                    @php
                        $tingkat = strtolower($row->nama_tingkat);
                        $badgeClass = 'badge-default';
                        if ($tingkat == 'ringan') $badgeClass = 'badge-ringan';
                        elseif ($tingkat == 'sedang') $badgeClass = 'badge-sedang';
                        elseif ($tingkat == 'berat') $badgeClass = 'badge-berat';
                        elseif ($tingkat == 'tinggi') $badgeClass = 'badge-tinggi';
                    @endphp
                    <span class="badge {{ $badgeClass }}">
                        {{ $row->nama_tingkat }}
                    </span>
                </td>
                <td class="text-center">{{ \Carbon\Carbon::parse($row->tanggal_laporan)->format('d/m/Y H:i') }}</td>
                <td>{{ $row->lokasi_nama ?? '-' }}</td>
                <td class="text-center">
                    @php
                        $status = $row->status_perbaikan ?? 'Belum Dijadwalkan';
                        $statusClass = 'badge-default';
                        if (in_array($status, ['Belum Dijadwalkan', 'Pending'])) {
                            $statusClass = 'badge-pending';
                        } elseif (in_array($status, ['In Progress', 'Menunggu Validasi'])) {
                            $statusClass = 'badge-progress';
                        } elseif ($status == 'Selesai') {
                            $statusClass = 'badge-selesai';
                        }
                    @endphp
                    <span class="badge {{ $statusClass }}">
                        {{ $status }}
                    </span>
                </td>
                <td>{{ $row->nama_teknisi ?? '-' }}</td>
                <td>
                    @if(!empty($row->deskripsi_kerusakan))
                        {{ $row->deskripsi_kerusakan }}
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td>
                    @if(!empty($row->deskripsi_pekerjaan_teknisi))
                        {{ $row->deskripsi_pekerjaan_teknisi }}
                    @else
                        <span class="text-muted">Belum ada perbaikan</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($row->tanggal_selesai_aktual)
                        {{ \Carbon\Carbon::parse($row->tanggal_selesai_aktual)->format('d/m/Y H:i') }}
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="12" class="no-data">Tidak ada data yang tersedia</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <div>Dokumen ini digenerate secara otomatis oleh Sistem Pemeliharaan Bus Listrik</div>
        <div>© {{ date('Y') }} - Semua hak dilindungi</div>
    </div>
</body>
</html>
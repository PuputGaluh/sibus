<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Laporan Kerusakan dan Perbaikan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            margin: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #dc2626;
            padding-bottom: 15px;
        }
        
        .header h1 {
            color: #dc2626;
            margin: 0 0 5px 0;
            font-size: 18pt;
        }
        
        .header h2 {
            color: #666;
            margin: 5px 0;
            font-size: 12pt;
            font-weight: normal;
        }
        
        .filter-info {
            background: #f8fafc;
            padding: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #dc2626;
            border-radius: 4px;
        }
        
        .filter-info strong {
            color: #dc2626;
        }
        
        .meta-info {
            text-align: right;
            margin-bottom: 20px;
            font-size: 9pt;
            color: #666;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        thead {
            background: #dc2626;
            color: white;
        }
        
        th {
            padding: 10px 8px;
            text-align: left;
            font-size: 9pt;
            font-weight: bold;
            border: 1px solid #dc2626;
        }
        
        td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 9pt;
        }
        
        tbody tr:nth-child(even) {
            background: #f8fafc;
        }
        
        tbody tr:hover {
            background: #fee2e2;
        }
        
        .text-center {
            text-align: center;
        }
        
        .badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 8pt;
            font-weight: bold;
            display: inline-block;
        }
        
        .badge-ringan {
            background: #10b981;
            color: white;
        }
        
        .badge-sedang {
            background: #f59e0b;
            color: white;
        }
        
        .badge-berat {
            background: #ef4444;
            color: white;
        }
        
        .badge-pending {
            background: #f59e0b;
            color: white;
        }
        
        .badge-progress {
            background: #3b82f6;
            color: white;
        }
        
        .badge-selesai {
            background: #10b981;
            color: white;
        }
        
        .badge-default {
            background: #64748b;
            color: white;
        }
        
        .text-muted {
            color: #999;
            font-style: italic;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #dc2626;
            text-align: center;
            font-size: 8pt;
            color: #666;
        }
        
        .summary {
            margin-top: 20px;
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        
        .summary h3 {
            color: #dc2626;
            margin: 0 0 10px 0;
            font-size: 11pt;
        }
        
        .summary-grid {
            display: table;
            width: 100%;
        }
        
        .summary-item {
            display: table-cell;
            text-align: center;
            padding: 10px;
            border-right: 1px solid #ddd;
        }
        
        .summary-item:last-child {
            border-right: none;
        }
        
        .summary-value {
            font-size: 20pt;
            font-weight: bold;
            color: #dc2626;
            margin-bottom: 5px;
        }
        
        .summary-label {
            font-size: 9pt;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REKAP LAPORAN KERUSAKAN DAN PERBAIKAN</h1>
        <h2>Sistem Pemeliharaan Bus Listrik</h2>
    </div>

    @if(isset($filterInfo) && $filterInfo['label'])
    <div class="filter-info">
        <strong>Periode:</strong> {{ $filterInfo['label'] }}
    </div>
    @endif

    <div class="meta-info">
        <div>Tanggal Cetak: {{ date('d F Y, H:i:s') }}</div>
        <div>Total Data: {{ count($rekap) }} Laporan</div>
    </div>

    <!-- Summary Statistics -->
    <div class="summary">
        <h3>Ringkasan Data</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-value">{{ count($rekap) }}</div>
                <div class="summary-label">Total Laporan</div>
            </div>
            <div class="summary-item">
                <div class="summary-value">{{ $rekap->whereIn('status_perbaikan', ['Belum Dijadwalkan', 'Pending'])->count() }}</div>
                <div class="summary-label">Belum/Pending</div>
            </div>
            <div class="summary-item">
                <div class="summary-value">{{ $rekap->whereIn('status_perbaikan', ['In Progress', 'Menunggu Validasi'])->count() }}</div>
                <div class="summary-label">Dalam Proses</div>
            </div>
            <div class="summary-item">
                <div class="summary-value">{{ $rekap->where('status_perbaikan', 'Selesai')->count() }}</div>
                <div class="summary-label">Selesai</div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="8%">ID</th>
                <th width="12%">Bus</th>
                <th width="12%">Kategori</th>
                <th width="10%">Tingkat</th>
                <th width="12%">Tgl Lapor</th>
                <th width="12%">Status</th>
                <th width="12%">Teknisi</th>
                <th width="12%">Tgl Selesai</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rekap as $index => $row)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">#{{ $row->id_laporan }}</td>
                <td><strong>{{ $row->nama_bus }}</strong></td>
                <td>{{ $row->nama_kategori }}</td>
                <td class="text-center">
                    <span class="badge 
                        @if(strtolower($row->nama_tingkat) == 'ringan') badge-ringan
                        @elseif(strtolower($row->nama_tingkat) == 'sedang') badge-sedang
                        @else badge-berat
                        @endif">
                        {{ $row->nama_tingkat }}
                    </span>
                </td>
                <td class="text-center">{{ \Carbon\Carbon::parse($row->tanggal_laporan)->format('d/m/Y H:i') }}</td>
                <td class="text-center">
                    <span class="badge 
                        @if($row->status_perbaikan == 'Belum Dijadwalkan' || $row->status_perbaikan == 'Pending') badge-pending
                        @elseif($row->status_perbaikan == 'In Progress' || $row->status_perbaikan == 'Menunggu Validasi') badge-progress
                        @elseif($row->status_perbaikan == 'Selesai') badge-selesai
                        @else badge-default
                        @endif">
                        {{ $row->status_perbaikan ?? 'Belum Dijadwalkan' }}
                    </span>
                </td>
                <td>{{ $row->nama_teknisi ?? '-' }}</td>
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
                <td colspan="9" class="text-center">Tidak ada data yang tersedia</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div>Dokumen ini digenerate secara otomatis oleh Sistem Pemeliharaan Bus Listrik</div>
        <div>© {{ date('Y') }} - Semua hak dilindungi</div>
    </div>
</body>
</html>
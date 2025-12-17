<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class RekapController extends Controller
{
    /**
     * TAMPILAN REKAP
     */
    public function index(Request $request)
    {
        $query = DB::table('laporan_kerusakan as lk')
            ->leftJoin('laporan_perbaikan as lp', 'lk.id_laporan', '=', 'lp.id_laporan')
            ->leftJoin('bus', 'lk.id_bus', '=', 'bus.id_bus')
            ->leftJoin('kategori_kerusakan as kk', 'lk.id_kategori', '=', 'kk.id_kategori')
            ->leftJoin('tingkat_kerusakan as tk', 'lk.id_tingkat', '=', 'tk.id_tingkat')
            ->leftJoin('users as teknisi', 'lp.id_teknisi', '=', 'teknisi.id_user')
            ->select(
                'lk.id_laporan',
                'bus.nama_bus',
                'kk.nama_kategori',
                'tk.nama_tingkat',
                'lk.created_at as tanggal_laporan',
                'lk.lokasi_nama',
                'lp.status_perbaikan',
                'lp.deskripsi_pekerjaan_teknisi',
                'lp.tanggal_selesai_aktual',
                'teknisi.name as nama_teknisi'
            )
            ->orderBy('lk.created_at', 'desc');

        // FILTER BERDASARKAN TIPE
        if ($request->filter_type) {
            switch ($request->filter_type) {
                case 'minggu':
                    $query->whereBetween('lk.created_at', [
                        now()->startOfWeek(),
                        now()->endOfWeek()
                    ]);
                    break;
                
                case 'bulan':
                    $bulan = $request->bulan ?? date('m');
                    $tahun = $request->tahun ?? date('Y');
                    $query->whereMonth('lk.created_at', $bulan)
                        ->whereYear('lk.created_at', $tahun);
                    break;
                
                case 'tahun':
                    $tahun = $request->tahun ?? date('Y');
                    $query->whereYear('lk.created_at', $tahun);
                    break;
                
                case 'custom':
                    if ($request->start_date && $request->end_date) {
                        $query->whereBetween('lk.created_at', [
                            $request->start_date,
                            $request->end_date
                        ]);
                    }
                    break;
            }
        }

        $data = $query->get();
        $filterInfo = $this->getFilterInfo($request);

        return view('rekap.index', compact('data', 'filterInfo'));    
    }

    

    /**
     * EXPORT PDF
     */
    public function exportPdf(Request $request)
    {
        $rekap = $this->getRekapData($request);
        $filterInfo = $this->getFilterInfo($request);

        $pdf = Pdf::loadView('rekap.pdf', compact('rekap', 'filterInfo'))
            ->setPaper('A4', 'landscape');

        return $pdf->download('rekap-laporan-' . $filterInfo['filename'] . '.pdf');
    }


    /**
     * EXPORT CSV
     */
    public function exportCsv(Request $request)
    {
        $rekap = $this->getRekapData($request);
        $filterInfo = $this->getFilterInfo($request);

        $response = new StreamedResponse(function () use ($rekap) {
            $handle = fopen('php://output', 'w');

            // HEADER CSV
            fputcsv($handle, [
                'ID Laporan',
                'Nama Bus',
                'Kategori Kerusakan',
                'Tingkat Kerusakan',
                'Tanggal Lapor',
                'Lokasi',
                'Status Perbaikan',
                'Nama Teknisi',
                'Hasil Perbaikan',
                'Tanggal Selesai'
            ]);

            foreach ($rekap as $row) {
                fputcsv($handle, [
                    $row->id_laporan,
                    $row->nama_bus,
                    $row->nama_kategori,
                    $row->nama_tingkat,
                    $row->tanggal_laporan,
                    $row->lokasi_nama ?? '-',
                    $row->status_perbaikan ?? 'Belum Dijadwalkan',
                    $row->nama_teknisi ?? '-',
                    $row->deskripsi_pekerjaan_teknisi ?? '-',
                    $row->tanggal_selesai_aktual ?? '-',
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set(
            'Content-Disposition',
            'attachment; filename="rekap-laporan-' . $filterInfo['filename'] . '.csv"'
        );

        return $response;
    }


    /**
     * QUERY REKAP (DIPAKAI ULANG)
     */
    private function getRekapData(Request $request)
    {
        $query = DB::table('laporan_kerusakan as lk')
            ->leftJoin('laporan_perbaikan as lp', 'lk.id_laporan', '=', 'lp.id_laporan')
            ->leftJoin('bus', 'lk.id_bus', '=', 'bus.id_bus')
            ->leftJoin('kategori_kerusakan as kk', 'lk.id_kategori', '=', 'kk.id_kategori')
            ->leftJoin('tingkat_kerusakan as tk', 'lk.id_tingkat', '=', 'tk.id_tingkat')
            ->leftJoin('users as teknisi', 'lp.id_teknisi', '=', 'teknisi.id_user')
            ->select(
                'lk.id_laporan',
                'bus.nama_bus',
                'kk.nama_kategori',
                'tk.nama_tingkat',
                'lk.created_at as tanggal_laporan',
                'lk.lokasi_nama',
                'lp.status_perbaikan',
                'lp.deskripsi_pekerjaan_teknisi',
                'lp.tanggal_selesai_aktual',
                'teknisi.name as nama_teknisi'
            )
            ->orderBy('lk.created_at', 'desc');

        // FILTER BERDASARKAN TIPE
        if ($request->filter_type) {
            switch ($request->filter_type) {
                case 'minggu':
                    $query->whereBetween('lk.created_at', [
                        now()->startOfWeek(),
                        now()->endOfWeek()
                    ]);
                    break;
                
                case 'bulan':
                    $bulan = $request->bulan ?? date('m');
                    $tahun = $request->tahun ?? date('Y');
                    $query->whereMonth('lk.created_at', $bulan)
                        ->whereYear('lk.created_at', $tahun);
                    break;
                
                case 'tahun':
                    $tahun = $request->tahun ?? date('Y');
                    $query->whereYear('lk.created_at', $tahun);
                    break;
                
                case 'custom':
                    if ($request->start_date && $request->end_date) {
                        $query->whereBetween('lk.created_at', [
                            $request->start_date,
                            $request->end_date
                        ]);
                    }
                    break;
            }
        }

        return $query->get();
    }

    /**
     * GET FILTER INFO FOR FILENAME & DISPLAY
     */
    private function getFilterInfo(Request $request)
    {
        $info = [
            'label' => 'Semua Data',
            'filename' => 'semua-data'
        ];

        if ($request->filter_type) {
            switch ($request->filter_type) {
                case 'minggu':
                    $info['label'] = 'Minggu Ini (' . now()->startOfWeek()->format('d M') . ' - ' . now()->endOfWeek()->format('d M Y') . ')';
                    $info['filename'] = 'minggu-ini';
                    break;
                
                case 'bulan':
                    $bulan = $request->bulan ?? date('m');
                    $tahun = $request->tahun ?? date('Y');
                    $namaBulan = [
                        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                        '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                        '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
                        '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                    ];
                    $info['label'] = $namaBulan[$bulan] . ' ' . $tahun;
                    $info['filename'] = strtolower($namaBulan[$bulan]) . '-' . $tahun;
                    break;
                
                case 'tahun':
                    $tahun = $request->tahun ?? date('Y');
                    $info['label'] = 'Tahun ' . $tahun;
                    $info['filename'] = 'tahun-' . $tahun;
                    break;
                
                case 'custom':
                    if ($request->start_date && $request->end_date) {
                        $info['label'] = date('d M Y', strtotime($request->start_date)) . ' - ' . date('d M Y', strtotime($request->end_date));
                        $info['filename'] = date('dmY', strtotime($request->start_date)) . '-' . date('dmY', strtotime($request->end_date));
                    }
                    break;
            }
        }

        return $info;
    }

    /**
     * API DETAIL REKAP - SIMPLE VERSION
     * Method ini sangat sederhana untuk menghindari error
     */
    public function detailRekap($id)
    {
        // Set response header ke JSON
        header('Content-Type: application/json');
        
        try {
            // Log untuk debugging
            Log::info('=== DETAIL REKAP START ===');
            Log::info('Request ID: ' . $id);
            
            // Query sederhana tanpa terlalu banyak join
            $laporan = DB::table('laporan_kerusakan')
                ->where('id_laporan', $id)
                ->first();
            
            Log::info('Laporan found: ' . ($laporan ? 'YES' : 'NO'));
            
            if (!$laporan) {
                Log::warning('Data not found for ID: ' . $id);
                return response()->json([
                    'error' => true,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            // Ambil data bus
            $bus = null;
            if (isset($laporan->id_bus)) {
                $bus = DB::table('bus')
                    ->where('id_bus', $laporan->id_bus)
                    ->first();
            }

            // Ambil data kategori
            $kategori = null;
            if (isset($laporan->id_kategori)) {
                $kategori = DB::table('kategori_kerusakan')
                    ->where('id_kategori', $laporan->id_kategori)
                    ->first();
            }

            // Ambil data tingkat
            $tingkat = null;
            if (isset($laporan->id_tingkat)) {
                $tingkat = DB::table('tingkat_kerusakan')
                    ->where('id_tingkat', $laporan->id_tingkat)
                    ->first();
            }

            // Ambil data pelapor
            $pelapor = null;
            if (isset($laporan->id_pelapor)) {
                $pelapor = DB::table('users')
                    ->where('id_user', $laporan->id_pelapor)
                    ->first();
            }

            // Ambil data perbaikan
            $perbaikan = DB::table('laporan_perbaikan')
                ->where('id_laporan', $id)
                ->first();

            // Ambil data teknisi jika ada perbaikan
            $teknisi = null;
            if ($perbaikan && isset($perbaikan->id_teknisi)) {
                $teknisi = DB::table('users')
                    ->where('id_user', $perbaikan->id_teknisi)
                    ->first();
            }

            // Susun response
            $response = [
                'id_laporan' => $laporan->id_laporan ?? null,
                'deskripsi_kerusakan' => $laporan->keterangan ?? null,
                'tanggal_laporan' => $laporan->created_at ?? null,
                'lokasi_nama' => $laporan->lokasi_nama ?? null,
                'nama_bus' => $bus->nama_bus ?? null,
                'nama_kategori' => $kategori->nama_kategori ?? null,
                'nama_tingkat' => $tingkat->nama_tingkat ?? null,
                'nama_pelapor' => $pelapor->name ?? null,
                'status_perbaikan' => $perbaikan->status_perbaikan ?? null,
                'deskripsi_pekerjaan_teknisi' => $perbaikan->deskripsi_pekerjaan_teknisi ?? null,
                'tanggal_selesai_aktual' => $perbaikan->tanggal_selesai_aktual ?? null,
                'nama_teknisi' => $teknisi->name ?? null
            ];

            Log::info('Response data prepared successfully');
            Log::info('Response: ' . json_encode($response));
            Log::info('=== DETAIL REKAP END ===');

            return response()->json($response, 200);

        } catch (\Exception $e) {
            Log::error('=== DETAIL REKAP ERROR ===');
            Log::error('Error Message: ' . $e->getMessage());
            Log::error('Error Line: ' . $e->getLine());
            Log::error('Error File: ' . $e->getFile());
            Log::error('Stack Trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan saat mengambil data',
                'detail' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}
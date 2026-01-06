<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Log;

class RekapController extends Controller
{
    /* =====================================================
     * HALAMAN REKAP
     * ===================================================== */
    public function index(Request $request)
    {
        $data = $this->buildRekapQuery($request)->get();
        $filterInfo = $this->getFilterInfo($request);

        return view('rekap.index', compact('data', 'filterInfo'));
    }

    /* =====================================================
     * EXPORT PDF
     * ===================================================== */
    public function exportPdf(Request $request)
    {
        $rekap = $this->buildRekapQuery($request)->get();
        $filterInfo = $this->getFilterInfo($request);

        $pdf = Pdf::loadView('rekap.pdf', compact('rekap', 'filterInfo'))
            ->setPaper('A4', 'landscape');

        return $pdf->download(
            'rekap-laporan-' . $filterInfo['filename'] . '.pdf'
        );
    }

    /* =====================================================
     * EXPORT CSV
     * ===================================================== */
    public function exportCsv(Request $request)
    {
        $rekap = $this->buildRekapQuery($request)->get();
        $filterInfo = $this->getFilterInfo($request);

        return new StreamedResponse(function () use ($rekap) {
            $handle = fopen('php://output', 'w');

            // Header CSV
            fputcsv($handle, [
                'ID Laporan',
                'Nama Bus',
                'Kategori Kerusakan',
                'Tingkat Kerusakan',
                'Tanggal Lapor',
                'Lokasi',
                'Keterangan Kerusakan',
                'Status Perbaikan',
                'Nama Teknisi',
                'Catatan Perbaikan',
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
                    $row->deskripsi_kerusakan ?? '-',
                    $row->status_perbaikan ?? 'Belum Dijadwalkan',
                    $row->nama_teknisi ?? '-',
                    $row->deskripsi_pekerjaan_teknisi ?? '-',
                    $row->tanggal_selesai_aktual ?? '-',
                ]);
            }

            fclose($handle);
        }, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="rekap-laporan.csv"',
        ]);
    }

    /* =====================================================
     * QUERY UTAMA (DIPAKAI ULANG)
     * ===================================================== */
    private function buildRekapQuery(Request $request)
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
                'lk.keterangan as deskripsi_kerusakan', // 🔥 tambahan penting
                'lp.status_perbaikan',
                'lp.deskripsi_pekerjaan_teknisi',
                'lp.tanggal_selesai_aktual',
                'teknisi.name as nama_teknisi'
            )
            ->orderBy('lk.created_at', 'desc');

        // FILTER
        if ($request->filter_type) {
            switch ($request->filter_type) {
                case 'minggu':
                    $query->whereBetween('lk.created_at', [
                        now()->startOfWeek(),
                        now()->endOfWeek()
                    ]);
                    break;

                case 'bulan':
                    $query->whereMonth('lk.created_at', $request->bulan ?? date('m'))
                          ->whereYear('lk.created_at', $request->tahun ?? date('Y'));
                    break;

                case 'tahun':
                    $query->whereYear('lk.created_at', $request->tahun ?? date('Y'));
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

        return $query;
    }

    /* =====================================================
     * INFO FILTER (LABEL & FILENAME)
     * ===================================================== */
    private function getFilterInfo(Request $request)
    {
        $info = [
            'label'    => 'Semua Data',
            'filename' => 'semua-data'
        ];

        if ($request->filter_type) {
            switch ($request->filter_type) {
                case 'minggu':
                    $info['label'] =
                        'Minggu Ini (' .
                        now()->startOfWeek()->format('d M') .
                        ' - ' .
                        now()->endOfWeek()->format('d M Y') .
                        ')';
                    $info['filename'] = 'minggu-ini';
                    break;

                case 'bulan':
                    $bulan = $request->bulan ?? date('m');
                    $tahun = $request->tahun ?? date('Y');
                    $namaBulan = [
                        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                        '04' => 'April',   '05' => 'Mei',      '06' => 'Juni',
                        '07' => 'Juli',    '08' => 'Agustus', '09' => 'September',
                        '10' => 'Oktober', '11' => 'November','12' => 'Desember'
                    ];
                    $info['label'] = $namaBulan[$bulan] . ' ' . $tahun;
                    $info['filename'] = strtolower($namaBulan[$bulan]) . '-' . $tahun;
                    break;

                case 'tahun':
                    $info['label'] = 'Tahun ' . ($request->tahun ?? date('Y'));
                    $info['filename'] = 'tahun-' . ($request->tahun ?? date('Y'));
                    break;

                case 'custom':
                    if ($request->start_date && $request->end_date) {
                        $info['label'] =
                            date('d M Y', strtotime($request->start_date)) .
                            ' - ' .
                            date('d M Y', strtotime($request->end_date));
                        $info['filename'] =
                            date('dmY', strtotime($request->start_date)) .
                            '-' .
                            date('dmY', strtotime($request->end_date));
                    }
                    break;
            }
        }

        return $info;
    }

    /* =====================================================
     * DETAIL REKAP (API)
     * ===================================================== */
    public function detailRekap($id)
    {
        try {
            $laporan = DB::table('laporan_kerusakan')->where('id_laporan', $id)->first();
            if (!$laporan) {
                return response()->json(['error' => true, 'message' => 'Data tidak ditemukan'], 404);
            }

            $perbaikan = DB::table('laporan_perbaikan')->where('id_laporan', $id)->first();

            return response()->json([
                'id_laporan'                   => $laporan->id_laporan,
                'deskripsi_kerusakan'          => $laporan->keterangan,
                'tanggal_laporan'              => $laporan->created_at,
                'lokasi_nama'                  => $laporan->lokasi_nama,
                'status_perbaikan'             => $perbaikan->status_perbaikan ?? null,
                'deskripsi_pekerjaan_teknisi'  => $perbaikan->deskripsi_pekerjaan_teknisi ?? null,
                'tanggal_selesai_aktual'       => $perbaikan->tanggal_selesai_aktual ?? null,
            ]);

        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'error'   => true,
                'message' => 'Terjadi kesalahan',
            ], 500);
        }
    }
}

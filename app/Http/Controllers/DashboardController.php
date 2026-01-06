<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Bus;
use App\Models\LaporanKerusakan;
use App\Models\LaporanPerbaikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $data = ['user' => $user];

        // ================================
        // DATA SESUAI ROLE
        // ================================
        switch ($user->role) {
            case 'Admin':
                $data = array_merge($data, $this->getAdminStats());
                break;

            case 'Dispatcher':
                $data = array_merge($data, $this->getDispatcherStats());
                $data = array_merge($data, $this->getBusRankingsComplete());
                break;

            case 'Teknisi':
                $data = array_merge($data, $this->getTeknisiStats());
                $data = array_merge($data, $this->getBusRankingsComplete());
                break;

            case 'Manajer':
                $data = array_merge($data, $this->getManajerStats());
                $data = array_merge($data, $this->getBusRankingsComplete());
                break;
        }

        return view('dashboard', $data);
    }

    /**
     * ================================
     * ADMIN DASHBOARD
     * ================================
     */
    private function getAdminStats()
    {
        // User Distribution by Role
        $userActivity = [
            'admin' => User::where('role', 'Admin')->count(),
            'dispatcher' => User::where('role', 'Dispatcher')->count(),
            'teknisi' => User::where('role', 'Teknisi')->count(),
            'manajer' => User::where('role', 'Manajer')->count(),
        ];

        // Bus Data & Status
        $totalBus = Bus::count();
        
        // Bus classified by damage count
        $busCondition = [
            'perfect' => DB::table('bus')
                ->leftJoin('laporan_kerusakan as lk', 'bus.id_bus', '=', 'lk.id_bus')
                ->select('bus.id_bus')
                ->groupBy('bus.id_bus')
                ->havingRaw('COUNT(lk.id_laporan) = 0')
                ->get()->count(),
            'good' => DB::table('bus')
                ->leftJoin('laporan_kerusakan as lk', 'bus.id_bus', '=', 'lk.id_bus')
                ->select('bus.id_bus')
                ->groupBy('bus.id_bus')
                ->havingRaw('COUNT(lk.id_laporan) BETWEEN 1 AND 5')
                ->get()->count(),
            'warning' => DB::table('bus')
                ->leftJoin('laporan_kerusakan as lk', 'bus.id_bus', '=', 'lk.id_bus')
                ->select('bus.id_bus')
                ->groupBy('bus.id_bus')
                ->havingRaw('COUNT(lk.id_laporan) BETWEEN 6 AND 10')
                ->get()->count(),
            'critical' => DB::table('bus')
                ->leftJoin('laporan_kerusakan as lk', 'bus.id_bus', '=', 'lk.id_bus')
                ->select('bus.id_bus')
                ->groupBy('bus.id_bus')
                ->havingRaw('COUNT(lk.id_laporan) > 10')
                ->get()->count(),
        ];

        // User Activity (last 7 days) - Dispatcher & Teknisi who created/worked on repairs
        $dailyActivity = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dailyActivity[] = [
                'date' => $date->format('D'),
                'dispatcher' => LaporanPerbaikan::whereDate('created_at', $date)
                    ->distinct('id_dispatcher')
                    ->count('id_dispatcher'),
                'teknisi' => LaporanPerbaikan::whereDate('tanggal_mulai_estimasi', $date)
                    ->distinct('id_teknisi')
                    ->count('id_teknisi'),
            ];
        }

        $busData = [
            'critical' => $busCondition['critical'],
        ];

        return [
            'totalUsers' => User::count(),
            'totalBus' => $totalBus,
            'totalLaporanKerusakan' => LaporanKerusakan::count(),
            'totalLaporanPerbaikan' => LaporanPerbaikan::count(),
            
            'userActivity' => $userActivity,
            'busCondition' => $busCondition,
            'dailyActivity' => $dailyActivity,
            'busData' => $busData,
        ];
    }

    /**
     * ================================
     * DISPATCHER DASHBOARD
     * ================================
     */
    private function getDispatcherStats()
    {
        $userId = auth()->id();

        // Tren Validasi 7 Hari Terakhir
        $validationTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $validationTrend[] = [
                'date' => $date->format('D'),
                'count' => LaporanPerbaikan::where('id_dispatcher', $userId)
                    ->whereDate('created_at', $date)
                    ->count(),
            ];
        }

        // Laporan by Tingkat Kerusakan
        $laporanByTingkat = LaporanKerusakan::select('id_tingkat', DB::raw('count(*) as total'))
            ->groupBy('id_tingkat')
            ->with('tingkat')
            ->get();

        // Status Perbaikan yang Divalidasi
        $validatedRepairsStatus = LaporanPerbaikan::where('id_dispatcher', $userId)
            ->select('status_perbaikan', DB::raw('count(*) as total'))
            ->groupBy('status_perbaikan')
            ->get();

        // Tingkat Urgensi
        $urgencyStats = [
            'critical' => LaporanKerusakan::where('status_proses', '!=', 'Selesai')
                ->whereHas('tingkat', function($q) {
                    $q->where('nama_tingkat', 'Berat');
                })->count(),
            'moderate' => LaporanKerusakan::where('status_proses', '!=', 'Selesai')
                ->whereHas('tingkat', function($q) {
                    $q->where('nama_tingkat', 'Sedang');
                })->count(),
            'low' => LaporanKerusakan::where('status_proses', '!=', 'Selesai')
                ->whereHas('tingkat', function($q) {
                    $q->where('nama_tingkat', 'Ringan');
                })->count(),
        ];

        return [
            'totalLaporanKerusakan' => LaporanKerusakan::count(),
            'laporanBelumDiproses' => LaporanKerusakan::where('status_proses', 'Belum Diproses')->count(),
            'laporanSedangDiproses' => LaporanKerusakan::where('status_proses', 'Sedang Diproses')->count(),
            'laporanSelesai' => LaporanKerusakan::where('status_proses', 'Selesai')->count(),
            'myValidatedRepairs' => LaporanPerbaikan::where('id_dispatcher', $userId)->count(),
            
            'validationTrend' => $validationTrend,
            'laporanByTingkat' => $laporanByTingkat,
            'validatedRepairsStatus' => $validatedRepairsStatus,
            'urgencyStats' => $urgencyStats,
        ];
    }

    /**
     * ================================
     * TEKNISI DASHBOARD
     * ================================
     */
    private function getTeknisiStats()
    {
        $userId = auth()->id();

        // Tren Pekerjaan 7 Hari Terakhir (HANYA TEKNISI YANG LOGIN)
        $workTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $workTrend[] = [
                'date' => $date->format('D'),
                'started' => LaporanPerbaikan::where('id_teknisi', $userId)
                    ->whereDate('tanggal_mulai_estimasi', $date)
                    ->count(),
                'completed' => LaporanPerbaikan::where('id_teknisi', $userId)
                    ->whereDate('tanggal_selesai_aktual', $date)
                    ->count(),
            ];
        }

        // Performa Perbaikan (Rata-rata waktu penyelesaian)
        $avgCompletionTime = LaporanPerbaikan::where('id_teknisi', $userId)
            ->whereNotNull('tanggal_selesai_aktual')
            ->whereNotNull('tanggal_mulai_estimasi')
            ->get()
            ->avg(function ($repair) {
                return Carbon::parse($repair->tanggal_selesai_aktual)
                    ->diffInDays(Carbon::parse($repair->tanggal_mulai_estimasi));
            });

        // Status Perbaikan Distribution
        $statusDistribution = [
            'menunggu' => LaporanPerbaikan::where('id_teknisi', $userId)
                ->where('status_perbaikan', 'Menunggu')
                ->count(),
            'sedang' => LaporanPerbaikan::where('id_teknisi', $userId)
                ->where('status_perbaikan', 'Sedang Diperbaiki')
                ->count(),
            'selesai' => LaporanPerbaikan::where('id_teknisi', $userId)
                ->where('status_perbaikan', 'Selesai')
                ->count(),
        ];

        // Prioritas yang Aktif (belum selesai) - HANYA MILIK TEKNISI INI
        $activePriorities = [
            'tinggi' => LaporanPerbaikan::where('id_teknisi', $userId)
                ->where('prioritas', 'Tinggi')
                ->where('status_perbaikan', '!=', 'Selesai')
                ->count(),
            'sedang' => LaporanPerbaikan::where('id_teknisi', $userId)
                ->where('prioritas', 'Sedang')
                ->where('status_perbaikan', '!=', 'Selesai')
                ->count(),
            'rendah' => LaporanPerbaikan::where('id_teknisi', $userId)
                ->where('prioritas', 'Rendah')
                ->where('status_perbaikan', '!=', 'Selesai')
                ->count(),
        ];

        return [
            'totalMyRepairs' => LaporanPerbaikan::where('id_teknisi', $userId)->count(),
            'myOngoingRepairs' => LaporanPerbaikan::where('id_teknisi', $userId)
                ->where('status_perbaikan', 'Sedang Diperbaiki')
                ->count(),
            'myCompletedRepairs' => LaporanPerbaikan::where('id_teknisi', $userId)
                ->where('status_perbaikan', 'Selesai')
                ->count(),
            'myPendingRepairs' => LaporanPerbaikan::where('id_teknisi', $userId)
                ->where('status_perbaikan', 'Menunggu')
                ->count(),
            'highPriorityCount' => $activePriorities['tinggi'],
            'mediumPriorityCount' => $activePriorities['sedang'],
            'lowPriorityCount' => $activePriorities['rendah'],
            
            'workTrend' => $workTrend,
            'avgCompletionTime' => round($avgCompletionTime, 1),
            'statusDistribution' => $statusDistribution,
            'activePriorities' => $activePriorities,
        ];
    }

    /**
     * ================================
     * MANAJER DASHBOARD
     * ================================
     */
    private function getManajerStats()
    {
        // Tren Bulanan (6 Bulan)
        $monthlyTrend = $this->getMonthlyTrend();

        // Rata-rata waktu perbaikan
        $avgRepairTime = LaporanPerbaikan::whereNotNull('tanggal_selesai_aktual')
            ->whereNotNull('tanggal_mulai_estimasi')
            ->get()
            ->avg(function ($repair) {
                return Carbon::parse($repair->tanggal_selesai_aktual)
                    ->diffInDays(Carbon::parse($repair->tanggal_mulai_estimasi));
            });

        // Kerusakan by Kategori (Top 5)
        $damageByCategory = LaporanKerusakan::select('id_kategori', DB::raw('count(*) as total'))
            ->groupBy('id_kategori')
            ->with('kategori')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Performance Metrics
        $performanceMetrics = [
            'completion_rate' => $this->getCompletionRate(),
            'avg_response_time' => $this->getAvgResponseTime(),
            'critical_pending' => LaporanKerusakan::where('status_proses', '!=', 'Selesai')
                ->whereHas('tingkat', function($q) {
                    $q->where('nama_tingkat', 'Berat');
                })->count(),
        ];

        // Efisiensi Teknisi (Top 5 berdasarkan jumlah perbaikan selesai)
        $teknisiPerformance = LaporanPerbaikan::where('status_perbaikan', 'Selesai')
            ->select('id_teknisi', DB::raw('count(*) as total'))
            ->groupBy('id_teknisi')
            ->with('teknisi')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return [
            'totalDamageReports' => LaporanKerusakan::count(),
            'completedRepairs' => LaporanPerbaikan::where('status_perbaikan', 'Selesai')->count(),
            'ongoingRepairs' => LaporanPerbaikan::where('status_perbaikan', 'Sedang Diperbaiki')->count(),
            'avgRepairTime' => round($avgRepairTime, 1),
            
            'monthlyTrend' => $monthlyTrend,
            'damageByCategory' => $damageByCategory,
            'performanceMetrics' => $performanceMetrics,
            'teknisiPerformance' => $teknisiPerformance,
        ];
    }

    /**
     * ================================
     * HELPER: Completion Rate
     * ================================
     */
    private function getCompletionRate()
    {
        $total = LaporanKerusakan::count();
        $completed = LaporanKerusakan::where('status_proses', 'Selesai')->count();
        
        return $total > 0 ? round(($completed / $total) * 100, 1) : 0;
    }

    /**
     * ================================
     * HELPER: Average Response Time
     * ================================
     */
    private function getAvgResponseTime()
    {
        $repairs = LaporanPerbaikan::whereNotNull('tanggal_mulai_estimasi')
            ->with('laporanKerusakan')
            ->get();

        if ($repairs->isEmpty()) {
            return 0;
        }

        $totalDays = 0;
        $count = 0;

        foreach ($repairs as $repair) {
            if ($repair->laporanKerusakan && $repair->laporanKerusakan->tanggal_lapor) {
                $days = Carbon::parse($repair->tanggal_mulai_estimasi)
                    ->diffInDays(Carbon::parse($repair->laporanKerusakan->tanggal_lapor));
                $totalDays += $days;
                $count++;
            }
        }

        return $count > 0 ? round($totalDays / $count, 1) : 0;
    }

    /**
     * ================================
     * PERANGKINGAN BUS SIMPLE (UNTUK TEKNISI & DISPATCHER)
     * ================================
     */
    private function getBusRankingsSimple()
    {
        // Bus dengan Total Kerusakan Terbanyak (Sering Rusak) - Top 3
        $busSeringRusak = DB::table('laporan_kerusakan as lk')
            ->join('bus', 'lk.id_bus', '=', 'bus.id_bus')
            ->select(
                'bus.id_bus',
                'bus.nama_bus',
                DB::raw('COUNT(lk.id_laporan) as total_kerusakan')
            )
            ->groupBy('bus.id_bus', 'bus.nama_bus')
            ->orderByDesc('total_kerusakan')
            ->limit(3)
            ->get();

        // Bus Paling Handal (Sedikit Kerusakan atau Tidak Ada) - Top 3
        $busPalingHandal = DB::table('bus')
            ->leftJoin('laporan_kerusakan as lk', 'bus.id_bus', '=', 'lk.id_bus')
            ->select(
                'bus.id_bus',
                'bus.nama_bus',
                DB::raw('COUNT(lk.id_laporan) as total_kerusakan')
            )
            ->groupBy('bus.id_bus', 'bus.nama_bus')
            ->orderBy('total_kerusakan', 'asc')
            ->limit(3)
            ->get();

        return [
            'busSeringRusak' => $busSeringRusak,
            'busPalingHandal' => $busPalingHandal,
        ];
    }

    /**
     * ================================
     * PERANGKINGAN BUS (UNTUK MANAJER)
     * ================================
     */
    private function getBusRankings()
    {
        // 1. Bus dengan Total Kerusakan Terbanyak (Sering Rusak) - Top 3 untuk card utama
        $busSeringRusak = DB::table('laporan_kerusakan as lk')
            ->join('bus', 'lk.id_bus', '=', 'bus.id_bus')
            ->join('tingkat_kerusakan as tk', 'lk.id_tingkat', '=', 'tk.id_tingkat')
            ->select(
                'bus.id_bus',
                'bus.nama_bus',
                DB::raw('COUNT(lk.id_laporan) as total_kerusakan'),
                DB::raw('SUM(CASE WHEN tk.nama_tingkat = "Berat" THEN 1 ELSE 0 END) as kerusakan_berat'),
                DB::raw('SUM(CASE WHEN tk.nama_tingkat = "Sedang" THEN 1 ELSE 0 END) as kerusakan_sedang'),
                DB::raw('SUM(CASE WHEN tk.nama_tingkat = "Ringan" THEN 1 ELSE 0 END) as kerusakan_ringan')
            )
            ->groupBy('bus.id_bus', 'bus.nama_bus')
            ->orderByDesc('total_kerusakan')
            ->limit(3)
            ->get();

        // 2. Bus Paling Handal (Sedikit Kerusakan atau Tidak Ada) - Top 3 untuk card utama
        $busPalingHandal = DB::table('bus')
            ->leftJoin('laporan_kerusakan as lk', 'bus.id_bus', '=', 'lk.id_bus')
            ->leftJoin('tingkat_kerusakan as tk', 'lk.id_tingkat', '=', 'tk.id_tingkat')
            ->select(
                'bus.id_bus',
                'bus.nama_bus',
                DB::raw('COUNT(lk.id_laporan) as total_kerusakan'),
                DB::raw('SUM(CASE WHEN tk.nama_tingkat = "Berat" THEN 1 ELSE 0 END) as kerusakan_berat'),
                DB::raw('SUM(CASE WHEN tk.nama_tingkat = "Sedang" THEN 1 ELSE 0 END) as kerusakan_sedang'),
                DB::raw('SUM(CASE WHEN tk.nama_tingkat = "Ringan" THEN 1 ELSE 0 END) as kerusakan_ringan')
            )
            ->groupBy('bus.id_bus', 'bus.nama_bus')
            ->orderBy('total_kerusakan', 'asc')
            ->limit(3)
            ->get();

        // 3. Summary Stats untuk Ranking Section
        $totalBusAktif = Bus::count();
        $busTanpaKerusakan = DB::table('bus')
            ->leftJoin('laporan_kerusakan as lk', 'bus.id_bus', '=', 'lk.id_bus')
            ->select('bus.id_bus')
            ->groupBy('bus.id_bus')
            ->havingRaw('COUNT(lk.id_laporan) = 0')
            ->get()
            ->count();

        return [
            // Ranking Utama (untuk tampilan card utama)
            'busSeringRusak' => $busSeringRusak,
            'busPalingHandal' => $busPalingHandal,
            
            // Summary Stats
            'totalBusAktif' => $totalBusAktif,
            'busTanpaKerusakan' => $busTanpaKerusakan,
        ];
    }

    /**
     * ================================
     * PERANGKINGAN BUS LENGKAP (UNTUK TEKNISI, DISPATCHER & MANAJER)
     * ================================
     */
    private function getBusRankingsComplete()
    {
        // Semua Bus dengan Total Kerusakan (Urut dari paling sedikit ke paling banyak)
        $allBuses = DB::table('bus')
            ->leftJoin('laporan_kerusakan as lk', 'bus.id_bus', '=', 'lk.id_bus')
            ->select(
                'bus.id_bus',
                'bus.nama_bus',
                DB::raw('COALESCE(COUNT(lk.id_laporan), 0) as total_kerusakan')
            )
            ->groupBy('bus.id_bus', 'bus.nama_bus')
            ->orderBy('total_kerusakan', 'asc')
            ->get();

        // Convert ke collection untuk bisa diakses di view
        $allBuses = collect($allBuses);

        return [
            'allBuses' => $allBuses,
        ];
    }

    /**
     * ================================
     * MONTHLY TREND (6 BULAN)
     * ================================
     */
    private function getMonthlyTrend()
    {
        $months = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();

            $months[] = [
                'month' => $date->format('M Y'),
                'damage_reports' => LaporanKerusakan::whereBetween('tanggal_lapor', [$start, $end])->count(),
                'completed_repairs' => LaporanPerbaikan::whereBetween('tanggal_selesai_aktual', [$start, $end])->count(),
            ];
        }

        return $months;
    }
}
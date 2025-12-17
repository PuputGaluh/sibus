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

        switch ($user->role) {
            case 'Admin':
                $data = array_merge($data, $this->getAdminStats());
                break;
            
            case 'Dispatcher':
                $data = array_merge($data, $this->getDispatcherStats());
                break;
            
            case 'Teknisi':
                $data = array_merge($data, $this->getTeknisiStats());
                break;
            
            case 'Manajer':
                $data = array_merge($data, $this->getManajerStats());
                break;
        }

        return view('dashboard', $data);
    }

    /**
     * Admin Dashboard Statistics
     */
    private function getAdminStats()
    {
        return [
            'totalUsers' => User::count(),
            'totalBus' => Bus::count(),
            'totalLaporanKerusakan' => LaporanKerusakan::count(),
            'totalLaporanPerbaikan' => LaporanPerbaikan::count(),
            
            // User by role
            'adminCount' => User::where('role', 'Admin')->count(),
            'dispatcherCount' => User::where('role', 'Dispatcher')->count(),
            'teknisiCount' => User::where('role', 'Teknisi')->count(),
            'manajerCount' => User::where('role', 'Manajer')->count(),
            
            // Recent activities
            'recentUsers' => User::orderBy('created_at', 'desc')->take(5)->get(),
            'recentBuses' => Bus::orderBy('created_at', 'desc')->take(5)->get(),
        ];
    }

    /**
     * Dispatcher Dashboard Statistics
     */
    private function getDispatcherStats()
    {
        $userId = auth()->id();
        
        return [
            // Laporan Kerusakan Stats
            'totalLaporanKerusakan' => LaporanKerusakan::count(),
            'laporanBelumDiproses' => LaporanKerusakan::where('status_proses', 'Belum Diproses')->count(),
            'laporanSedangDiproses' => LaporanKerusakan::where('status_proses', 'Sedang Diproses')->count(),
            'laporanSelesai' => LaporanKerusakan::where('status_proses', 'Selesai')->count(),
            
            // Perbaikan yang ditangani dispatcher ini
            'myValidatedRepairs' => LaporanPerbaikan::where('id_dispatcher', $userId)->count(),
            'pendingValidation' => LaporanPerbaikan::whereNull('id_dispatcher')->count(),
            
            // Bus Stats
            'totalBus' => Bus::count(),
            'busWithDamage' => LaporanKerusakan::where('status_proses', '!=', 'Selesai')
                ->distinct('id_bus')
                ->count('id_bus'),
            
            // Recent Laporan
            'recentLaporanKerusakan' => LaporanKerusakan::with(['bus', 'pelapor', 'kategori', 'tingkat'])
                ->orderBy('tanggal_lapor', 'desc')
                ->take(5)
                ->get(),
            
            // Laporan by Tingkat Kerusakan
            'laporanByTingkat' => LaporanKerusakan::select('id_tingkat', DB::raw('count(*) as total'))
                ->groupBy('id_tingkat')
                ->with('tingkat')
                ->get(),
        ];
    }

    /**
     * Teknisi Dashboard Statistics
     */
    private function getTeknisiStats()
    {
        $userId = auth()->id();
        
        return [
            // Perbaikan assigned to this teknisi
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
            
            // New damage reports (belum ada perbaikan)
            'newDamageReports' => LaporanKerusakan::whereDoesntHave('laporanPerbaikan')
                ->where('status_proses', 'Belum Diproses')
                ->count(),
            
            // My repairs list
            'myRepairsList' => LaporanPerbaikan::where('id_teknisi', $userId)
                ->with(['laporanKerusakan.bus', 'laporanKerusakan.kategori'])
                ->orderBy('prioritas', 'desc')
                ->orderBy('tanggal_mulai_estimasi', 'asc')
                ->take(10)
                ->get(),
            
            // Stats by priority
            'highPriorityCount' => LaporanPerbaikan::where('id_teknisi', $userId)
                ->where('prioritas', 'Tinggi')
                ->where('status_perbaikan', '!=', 'Selesai')
                ->count(),
            'mediumPriorityCount' => LaporanPerbaikan::where('id_teknisi', $userId)
                ->where('prioritas', 'Sedang')
                ->where('status_perbaikan', '!=', 'Selesai')
                ->count(),
            'lowPriorityCount' => LaporanPerbaikan::where('id_teknisi', $userId)
                ->where('prioritas', 'Rendah')
                ->where('status_perbaikan', '!=', 'Selesai')
                ->count(),
        ];
    }

    /**
     * Manajer Dashboard Statistics
     */
    private function getManajerStats()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        
        return [
            // Overall Stats
            'totalDamageReports' => LaporanKerusakan::count(),
            'totalRepairReports' => LaporanPerbaikan::count(),
            'completedRepairs' => LaporanPerbaikan::where('status_perbaikan', 'Selesai')->count(),
            'ongoingRepairs' => LaporanPerbaikan::where('status_perbaikan', 'Sedang Diperbaiki')->count(),
            
            // Today's Stats
            'todayDamageReports' => LaporanKerusakan::whereDate('tanggal_lapor', $today)->count(),
            'todayCompletedRepairs' => LaporanPerbaikan::whereDate('tanggal_selesai_aktual', $today)->count(),
            
            // This Month Stats
            'thisMonthDamageReports' => LaporanKerusakan::whereDate('tanggal_lapor', '>=', $thisMonth)->count(),
            'thisMonthCompletedRepairs' => LaporanPerbaikan::whereDate('tanggal_selesai_aktual', '>=', $thisMonth)->count(),
            
            // Bus Stats
            'totalBus' => Bus::count(),
            'busDamaged' => LaporanKerusakan::where('status_proses', '!=', 'Selesai')
                ->distinct('id_bus')
                ->count('id_bus'),
            
            // Damage by Category
            'damageByCategory' => LaporanKerusakan::select('id_kategori', DB::raw('count(*) as total'))
                ->groupBy('id_kategori')
                ->with('kategori')
                ->orderBy('total', 'desc')
                ->get(),
            
            // Damage by Tingkat
            'damageByTingkat' => LaporanKerusakan::select('id_tingkat', DB::raw('count(*) as total'))
                ->groupBy('id_tingkat')
                ->with('tingkat')
                ->orderBy('total', 'desc')
                ->get(),
            
            // Teknisi Performance
            'teknisiPerformance' => User::where('role', 'Teknisi')
                ->withCount([
                    'perbaikanTeknisi',
                    'perbaikanTeknisi as completed_repairs' => function($query) {
                        $query->where('status_perbaikan', 'Selesai');
                    }
                ])
                ->get(),
            
            // Average repair time (in days)
            'avgRepairTime' => LaporanPerbaikan::whereNotNull('tanggal_selesai_aktual')
                ->whereNotNull('tanggal_mulai_estimasi')
                ->get()
                ->avg(function($repair) {
                    return Carbon::parse($repair->tanggal_selesai_aktual)
                        ->diffInDays(Carbon::parse($repair->tanggal_mulai_estimasi));
                }),
            
            // Monthly trend (last 6 months)
            'monthlyTrend' => $this->getMonthlyTrend(),
            
            // Recent completed repairs
            'recentCompletedRepairs' => LaporanPerbaikan::where('status_perbaikan', 'Selesai')
                ->with(['laporanKerusakan.bus', 'teknisi'])
                ->orderBy('tanggal_selesai_aktual', 'desc')
                ->take(5)
                ->get(),
        ];
    }

    /**
     * Get monthly trend for last 6 months
     */
    private function getMonthlyTrend()
    {
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();
            
            $months[] = [
                'month' => $date->format('M Y'),
                'damage_reports' => LaporanKerusakan::whereBetween('tanggal_lapor', [$monthStart, $monthEnd])->count(),
                'completed_repairs' => LaporanPerbaikan::whereBetween('tanggal_selesai_aktual', [$monthStart, $monthEnd])->count(),
            ];
        }
        
        return $months;
    }
}
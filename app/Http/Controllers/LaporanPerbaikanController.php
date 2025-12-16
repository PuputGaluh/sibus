<?php

namespace App\Http\Controllers;

use App\Models\LaporanPerbaikan;
use App\Models\LaporanKerusakan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanPerbaikanController extends Controller
{
    /**
     * ===============================
     * HALAMAN LAPORAN PERBAIKAN
     * (Dispatcher, Teknisi, Manajer)
     * ===============================
     */
    public function index()
    {
        $user = Auth::user();

        $query = LaporanPerbaikan::with([
            'laporanKerusakan.bus',
            'teknisi',
            'dispatcher'
        ])->latest();

        // Teknisi hanya lihat miliknya
        if ($user->role === 'Teknisi') {
            $query->where('id_teknisi', $user->id_user);
        }

        $perbaikan = $query->get();

        return view('laporan_perbaikan.index', [
            'perbaikan' => $perbaikan,
            'laporanKerusakan' => LaporanKerusakan::where('status_proses', 'Dijadwalkan')->get(),
            'teknisi' => User::where('role', 'Teknisi')->get(),
            'role' => $user->role
        ]);
    }

    /**
     * ===============================
     * SIMPAN LAPORAN PERBAIKAN
     * (DISPATCHER)
     * ===============================
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'Dispatcher') {
            abort(403);
        }

        $request->validate([
            'id_laporan' => 'required|integer',
            'id_teknisi' => 'required|integer',
            'prioritas' => 'required|in:Rendah,Sedang,Tinggi,Mendesak',
            'tanggal_mulai_estimasi' => 'required|date',
            'tanggal_selesai_estimasi' => 'required|date|after_or_equal:tanggal_mulai_estimasi',
            'catatan' => 'nullable|string'
        ]);

        // ❌ CEK BENTROK JADWAL TEKNISI
        $bentrok = LaporanPerbaikan::where('id_teknisi', $request->id_teknisi)
            ->where(function ($q) use ($request) {
                $q->whereBetween('tanggal_mulai_estimasi', [
                    $request->tanggal_mulai_estimasi,
                    $request->tanggal_selesai_estimasi
                ])->orWhereBetween('tanggal_selesai_estimasi', [
                    $request->tanggal_mulai_estimasi,
                    $request->tanggal_selesai_estimasi
                ]);
            })->exists();

        if ($bentrok) {
            return back()->withErrors([
                'id_teknisi' => 'Teknisi sudah memiliki jadwal pada tanggal tersebut'
            ]);
        }

        LaporanPerbaikan::create([
            'id_laporan' => $request->id_laporan,
            'id_dispatcher' => Auth::id(),
            'id_teknisi' => $request->id_teknisi,
            'tanggal_validasi' => now(),
            'prioritas' => $request->prioritas,
            'catatan' => $request->catatan,
            'tanggal_mulai_estimasi' => $request->tanggal_mulai_estimasi,
            'tanggal_selesai_estimasi' => $request->tanggal_selesai_estimasi,
            'status_perbaikan' => 'Pending'
        ]);

        return redirect()->back()->with('success', 'Laporan perbaikan berhasil dibuat');
    }

    /**
     * ===============================
     * DETAIL LAPORAN PERBAIKAN
     * ===============================
     */
    public function show($id)
    {
        $data = LaporanPerbaikan::with([
            'laporanKerusakan.bus',
            'laporanKerusakan.kategori',
            'laporanKerusakan.tingkat',
            'teknisi',
            'dispatcher'
        ])->findOrFail($id);

        return response()->json($data);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_perbaikan' => 'required|in:In Progress'
        ]);

        $perbaikan = LaporanPerbaikan::findOrFail($id);

        if ($perbaikan->id_teknisi !== Auth::user()->id_user) {
            abort(403);
        }

        $perbaikan->update([
            'status_perbaikan' => 'In Progress'
        ]);

        return back()->with('success', 'Perbaikan dimulai');
    }

    public function submitHasil(Request $request, $id)
    {
        $request->validate([
            'deskripsi_pekerjaan_teknisi' => 'required|string'
        ]);

        $perbaikan = LaporanPerbaikan::findOrFail($id);

        if ($perbaikan->id_teknisi !== Auth::user()->id_user) {
            abort(403);
        }

        $perbaikan->update([
            'deskripsi_pekerjaan_teknisi' => $request->deskripsi_pekerjaan_teknisi,
            'tanggal_selesai_aktual' => now(),
            'status_perbaikan' => 'Menunggu Validasi'
        ]);

        return back()->with('success', 'Hasil perbaikan dikirim ke dispatcher');
    }

    public function setSelesai($id)
    {
        $perbaikan = LaporanPerbaikan::findOrFail($id);

        if ($perbaikan->status_perbaikan !== 'Menunggu Validasi') {
            return back()->withErrors('Belum ada hasil perbaikan dari teknisi');
        }

        $perbaikan->update([
            'status_perbaikan' => 'Selesai'
        ]);

        $perbaikan->laporanKerusakan->update([
            'status_proses' => 'Selesai'
        ]);

        return back()->with('success', 'Perbaikan diselesaikan');
    }



}

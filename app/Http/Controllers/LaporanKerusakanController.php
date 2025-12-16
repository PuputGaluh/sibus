<?php

namespace App\Http\Controllers;

use App\Models\LaporanKerusakan;
use App\Models\LaporanPerbaikan;
use App\Models\Bus;
use App\Models\KategoriKerusakan;
use App\Models\TingkatKerusakan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanKerusakanController extends Controller
{
    /**
     * =================================================
     * HALAMAN LAPORAN KERUSAKAN
     * (Teknisi, Dispatcher, Manajer)
     * =================================================
     */
    public function index()
    {
        $user = Auth::user();

        $laporan = LaporanKerusakan::with([
            'bus',
            'pelapor',
            'kategori',
            'tingkat'
        ])->latest()->get();

        return view('laporan_kerusakan.index', [
            'laporan'  => $laporan,
            'bus'      => Bus::all(),
            'kategori' => KategoriKerusakan::all(),
            'tingkat'  => TingkatKerusakan::all(),
            'teknisi'  => User::where('role', 'Teknisi')->get(),
            'role'     => $user->role
        ]);
    }

    /**
     * =================================================
     * SIMPAN LAPORAN KERUSAKAN (TEKNISI)
     * =================================================
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'Teknisi') {
            abort(403);
        }

        $request->validate([
            'id_bus' => 'required|integer',
            'id_kategori' => 'required|integer',
            'id_tingkat' => 'required|integer',
            'status_keberangkatan' => 'required',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image|max:2048'
        ]);

        $foto = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('laporan_kerusakan', 'public');
        }

        LaporanKerusakan::create([
            'id_pelapor' => Auth::id(),
            'id_bus' => $request->id_bus,
            'id_kategori' => $request->id_kategori,
            'id_tingkat' => $request->id_tingkat,
            'status_keberangkatan' => $request->status_keberangkatan,
            'keterangan' => $request->keterangan,
            'tanggal_lapor' => now(),
            'foto' => $foto,
            'status_proses' => 'Dilaporkan'
        ]);

        return redirect()->back()
            ->with('success', 'Laporan kerusakan berhasil ditambahkan');
    }

    /**
     * =================================================
     * DETAIL LAPORAN (SEMUA ROLE)
     * =================================================
     */
    public function show($id)
    {
        $laporan = LaporanKerusakan::with([
            'bus',
            'pelapor',
            'kategori',
            'tingkat'
        ])->findOrFail($id);

        return response()->json($laporan);
    }

    /**
     * =================================================
     * VALIDASI LAPORAN (DISPATCHER)
     * =================================================
     */
    public function validasi($id)
    {
        if (Auth::user()->role !== 'Dispatcher') {
            abort(403);
        }

        $laporan = LaporanKerusakan::findOrFail($id);

        $laporan->update([
            'status_proses' => 'Validasi Diproses'
        ]);

        return redirect()->back()
            ->with('success', 'Laporan berhasil divalidasi');
    }

    /**
     * =================================================
     * JADWALKAN PERBAIKAN (DISPATCHER)
     * + AUTO CREATE LAPORAN PERBAIKAN
     * =================================================
     */
    public function jadwalkan(Request $request, $id)
    {
        // dd('MASUK CONTROLLER', $request->all());

        if (Auth::user()->role !== 'Dispatcher') {
            abort(403);
        }

        $request->validate([
            'id_teknisi' => 'required|integer|exists:users,id_user',
            'prioritas' => 'required|in:Rendah,Sedang,Tinggi,Mendesak',
            'tanggal_mulai_estimasi' => 'required|date',
            'tanggal_selesai_estimasi' => 'required|date|after_or_equal:tanggal_mulai_estimasi',
            'catatan' => 'nullable|string'
        ]);

        $laporan = LaporanKerusakan::findOrFail($id);

        /**
         * ❌ CEK BENTROK JADWAL
         * - Teknisi sama
         * - Tanggal overlap
         */
        $bentrok = LaporanPerbaikan::where('id_teknisi', $request->id_teknisi)
            ->where(function ($q) use ($request) {
                $q->whereBetween('tanggal_mulai_estimasi', [
                    $request->tanggal_mulai_estimasi,
                    $request->tanggal_selesai_estimasi
                ])
                ->orWhereBetween('tanggal_selesai_estimasi', [
                    $request->tanggal_mulai_estimasi,
                    $request->tanggal_selesai_estimasi
                ])
                ->orWhere(function ($q) use ($request) {
                    $q->where('tanggal_mulai_estimasi', '<=', $request->tanggal_mulai_estimasi)
                      ->where('tanggal_selesai_estimasi', '>=', $request->tanggal_selesai_estimasi);
                });
            })->exists();

        if ($bentrok) {
            return redirect()->back()->withErrors([
                'id_teknisi' => 'Teknisi sudah memiliki jadwal pada tanggal tersebut'
            ]);
        }

        /**
         * ✅ BUAT LAPORAN PERBAIKAN
         */
        LaporanPerbaikan::create([
            'id_laporan' => $laporan->id_laporan,
            'id_dispatcher' => Auth::id(),
            'id_teknisi' => $request->id_teknisi,
            'tanggal_validasi' => now(),
            'prioritas' => $request->prioritas,
            'catatan' => $request->catatan,
            'tanggal_mulai_estimasi' => $request->tanggal_mulai_estimasi,
            'tanggal_selesai_estimasi' => $request->tanggal_selesai_estimasi,
            'status_perbaikan' => 'Pending'
        ]);

        /**
         * ✅ UPDATE STATUS LAPORAN KERUSAKAN
         */
        $laporan->update([
            'status_proses' => 'Dijadwalkan'
        ]);

        return redirect()->route('laporan_perbaikan.index')
            ->with('success', 'Perbaikan berhasil dijadwalkan');
    }
}

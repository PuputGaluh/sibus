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
            'countMenunggu' => $perbaikan->where('status_perbaikan', 'Pending')->count(),
            'countDalamProses' => $perbaikan->where('status_perbaikan', 'In Progress')->count(),
            'countSelesai' => $perbaikan->where('status_perbaikan', 'Selesai')->count(),
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

        // ✅ Tambahkan prioritas ke validasi
        $validated = $request->validate([
            'id_laporan' => 'required|integer|exists:laporan_kerusakan,id_laporan',
            'id_teknisi' => 'required|integer|exists:users,id_user',
            'tanggal_mulai_estimasi' => 'required|date',
            'tanggal_selesai_estimasi' => 'required|date|after_or_equal:tanggal_mulai_estimasi',
            'prioritas' => 'required|in:Rendah,Sedang,Tinggi',
            'catatan' => 'nullable|string'
        ]);

        // 🔎 Ambil laporan kerusakan
        $laporan = LaporanKerusakan::findOrFail($request->id_laporan);

        // ✅ Cek bentrok jadwal
        $bentrok = LaporanPerbaikan::where('id_teknisi', $request->id_teknisi)
            ->whereIn('status_perbaikan', ['Pending', 'In Progress'])
            ->where(function ($q) use ($request) {
                $q->whereBetween('tanggal_mulai_estimasi', [$request->tanggal_mulai_estimasi, $request->tanggal_selesai_estimasi])
                ->orWhereBetween('tanggal_selesai_estimasi', [$request->tanggal_mulai_estimasi, $request->tanggal_selesai_estimasi])
                ->orWhere(function($sub) use ($request) {
                    $sub->where('tanggal_mulai_estimasi', '<=', $request->tanggal_mulai_estimasi)
                        ->where('tanggal_selesai_estimasi', '>=', $request->tanggal_selesai_estimasi);
                });
            })
            ->exists();

        if ($bentrok) {
            return back()->withErrors([
                'error' => 'Teknisi sudah memiliki jadwal pada rentang waktu tersebut'
            ])->withInput();
        }

        // ✅ SIMPAN LAPORAN PERBAIKAN
        try {
            LaporanPerbaikan::create([
                'id_laporan' => $laporan->id_laporan,
                'id_dispatcher' => Auth::user()->id_user,
                'id_teknisi' => $request->id_teknisi,
                'tanggal_validasi' => now(),
                'prioritas' => $request->prioritas, // ✅ Langsung dari request
                'catatan' => $request->catatan,
                'tanggal_mulai_estimasi' => $request->tanggal_mulai_estimasi, // ✅ Tidak perlu konversi
                'tanggal_selesai_estimasi' => $request->tanggal_selesai_estimasi, // ✅ Tidak perlu konversi
                'status_perbaikan' => 'Pending'
            ]);

            // 🔄 Update status laporan kerusakan
            $laporan->update([
                'status_proses' => 'Dijadwalkan'
            ]);

            return redirect()->back()->with('success', 'Perbaikan berhasil dijadwalkan');
            
        } catch (\Exception $e) {
            // ✅ Tangkap error untuk debugging
            return back()->withErrors([
                'error' => 'Gagal menyimpan: ' . $e->getMessage()
            ])->withInput();
        }
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
            'deskripsi_pekerjaan_teknisi' => 'required|string',
            'gambar_perbaikan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
        ], [
            'gambar_perbaikan.image' => 'File harus berupa gambar',
            'gambar_perbaikan.mimes' => 'Format gambar harus JPEG, PNG, JPG, atau GIF',
            'gambar_perbaikan.max' => 'Ukuran gambar maksimal 5MB'
        ]);

        $perbaikan = LaporanPerbaikan::findOrFail($id);

        if ($perbaikan->id_teknisi !== Auth::user()->id_user) {
            abort(403);
        }

        $dataUpdate = [
            'deskripsi_pekerjaan_teknisi' => $request->deskripsi_pekerjaan_teknisi,
            'tanggal_selesai_aktual' => now(),
            'status_perbaikan' => 'Menunggu Validasi'
        ];

        // Handle upload gambar
        if ($request->hasFile('gambar_perbaikan')) {
            $file = $request->file('gambar_perbaikan');
            $filename = 'perbaikan_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('perbaikan', $filename, 'public');
            $dataUpdate['gambar_perbaikan'] = $filename;
        }

        $perbaikan->update($dataUpdate);

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

    private function mapPrioritasByTingkat(int $idTingkat): string
    {
        return match ($idTingkat) {
            1 => 'Rendah',   // Ringan
            2 => 'Sedang',   // Sedang
            3 => 'Tinggi',   // Berat
            default => 'Rendah',
        };
    }

        public function teknisiSchedule($id)
    {
        $schedules = LaporanPerbaikan::where('id_teknisi', $id)
            ->whereIn('status_perbaikan', ['Pending', 'In Progress', 'Menunggu Validasi'])
            ->select(
                'tanggal_mulai_estimasi',
                'tanggal_selesai_estimasi'
            )
            ->get();

        return response()->json([
            'schedules' => $schedules
        ]);
    }

}

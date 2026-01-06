<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use Illuminate\Http\Request;

class BusController extends Controller
{
    public function index()
    {
        return view('admin.bus.index', [
            'bus' => Bus::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bus' => 'required',
            'deskripsi' => 'nullable|string',
            'kapasitas_penumpang' => 'nullable|string',
            'kapasitas_baterai' => 'nullable|string',
            'jenis_baterai' => 'nullable|string'
        ]);

        Bus::create([
            'nama_bus' => $request->nama_bus,
            'deskripsi' => $request->deskripsi,
            'kapasitas_penumpang' => $request->kapasitas_penumpang,
            'kapasitas_baterai' => $request->kapasitas_baterai,
            'jenis_baterai' => $request->jenis_baterai
        ]);

        return back()->with('success', 'Data bus berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_bus' => 'required',
            'deskripsi' => 'nullable|string',
            'kapasitas_penumpang' => 'nullable|string',
            'kapasitas_baterai' => 'nullable|string',
            'jenis_baterai' => 'nullable|string'
        ]);

        Bus::where('id_bus', $id)->update([
            'nama_bus' => $request->nama_bus,
            'deskripsi' => $request->deskripsi,
            'kapasitas_penumpang' => $request->kapasitas_penumpang,
            'kapasitas_baterai' => $request->kapasitas_baterai,
            'jenis_baterai' => $request->jenis_baterai
        ]);

        return back()->with('success', 'Data bus berhasil diperbarui');
    }

    public function destroy($id)
    {
        Bus::where('id_bus', $id)->delete();
        return back()->with('success', 'Data bus berhasil dihapus');
    }
}

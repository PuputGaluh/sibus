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
            'nama_bus' => 'required'
        ]);

        Bus::create([
            'nama_bus' => $request->nama_bus
        ]);

        return back()->with('success', 'Data bus berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        Bus::where('id_bus', $id)->update([
            'nama_bus' => $request->nama_bus
        ]);

        return back()->with('success', 'Data bus berhasil diperbarui');
    }

    public function destroy($id)
    {
        Bus::where('id_bus', $id)->delete();
        return back()->with('success', 'Data bus berhasil dihapus');
    }
}

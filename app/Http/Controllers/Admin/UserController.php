<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.user.index', [
            'users' => User::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username',
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required',
            'password' => 'required|min:6'
        ]);

        User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'User berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $user = User::where('id_user', $id)->first();

        // Validasi
        $validated = $request->validate([
            'name' => 'required|string',
            'username' => 'required|string|unique:users,username,' . $id . ',id_user',
            'role' => 'required|string',
            'password' => 'nullable|min:6|confirmed'
        ], [
            'username.unique' => 'Username sudah digunakan oleh user lain.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.'
        ]);

        // Siapkan data untuk update
        $updateData = [
            'name' => $request->name,
            'username' => $request->username,
            'role' => $request->role
        ];

        // Jika password diisi, hash dan tambahkan ke update data
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        User::where('id_user', $id)->update($updateData);

        return back()->with('success', 'User berhasil diperbarui');
    }

    public function destroy($id)
    {
        $user = User::where('id_user', $id)->first();
        
        // Cek apakah user adalah admin
        if ($user->role === 'Admin') {
            $adminCount = User::where('role', 'Admin')->count();
            // Jika hanya ada 1 admin, jangan boleh dihapus
            if ($adminCount <= 1) {
                return back()->with('error', 'Tidak dapat menghapus admin terakhir. Sistem harus memiliki minimal 1 admin.');
            }
        }
        
        User::where('id_user', $id)->delete();
        return back()->with('success', 'User berhasil dihapus');
    }
}

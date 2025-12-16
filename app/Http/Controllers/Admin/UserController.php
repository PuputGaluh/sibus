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
        User::where('id_user', $id)->update([
            'name' => $request->name,
            'role' => $request->role
        ]);

        return back()->with('success', 'User berhasil diperbarui');
    }

    public function destroy($id)
    {
        User::where('id_user', $id)->delete();
        return back()->with('success', 'User berhasil dihapus');
    }
}

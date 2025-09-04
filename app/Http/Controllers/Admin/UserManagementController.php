<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index()
    {
        // Ambil user dengan role_id 2 (Petugas PST)
        $users = User::where('role_id', 2)->get();
        return view('admin.petugas.index', compact('users'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->role_id != 2) {
            return redirect()->route('admin.petugas.index')->with('error', 'User tidak dapat dihapus.');
        }

        $user->delete();

        return redirect()->route('admin.petugas.index')->with('success', 'User berhasil dihapus.');
    }
}

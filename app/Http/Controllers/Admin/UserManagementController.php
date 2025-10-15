<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::query()
            ->where('role_id', 2) // Hanya user dengan role_id 2 (Petugas)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('nip', 'like', "%{$search}%");
                });
            })
            ->paginate(10)
            ->withQueryString(); // Biar search term tetap di URL saat pagination

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
    public function toggle($id)
    {
        $user = User::findOrFail($id);

        // Pastikan hanya petugas (role_id = 2) yang bisa diubah statusnya
        if ($user->role_id != 2) {
            return redirect()->back()->with('error', 'Hanya petugas yang bisa diaktifkan/nonaktifkan.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Petugas {$user->nama_lengkap} berhasil {$status}.");
    }

}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('admin.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_lengkap' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\s]+$/', // hanya huruf & spasi
            ],
            'nip' => [
                'required',
                'digits:18',             // hanya angka & harus tepat 15 digit
                'unique:' . User::class,
            ],
            'username' => [
                'required',
                'string',
                'max:255',
                'unique:' . User::class,
            ],
            'password' => [
                'required',
                'confirmed',
                Rules\Password::min(8),  // minimal 8 karakter
            ],
            'email' => [
                'nullable',
                'email',
                'unique:' . User::class,
            ],
            'no_hp' => [
                'nullable',
                'regex:/^[0-9]{10,15}$/', // hanya angka, panjang 10–15 digit
            ],
        ], [
            'nama_lengkap.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
            'nip.digits' => 'NIP harus terdiri dari 18 angka.',
            'no_hp.regex' => 'Nomor HP hanya boleh berisi angka (10–15 digit).',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        // BUAT USER BARU - INI YANG PERLU DITAMBAHKAN
        $user = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'nip' => $request->nip,
            'username' => $request->username,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
            'role_id' => 2, // Default role untuk petugas PST, sesuaikan dengan kebutuhan
        ]);

        // event(new Registered($user));

        return redirect()->route('admin.petugas.index')->with('success', 'User berhasil didaftarkan.');
    }
}

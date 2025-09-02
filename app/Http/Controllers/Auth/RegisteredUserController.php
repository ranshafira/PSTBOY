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
        'nama_lengkap' => ['required', 'string', 'max:255'],
        'nip' => ['required', 'string', 'max:18', 'unique:'.User::class],
        'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $user = User::create([
        'nama_lengkap' => $request->nama_lengkap,
        'nip' => $request->nip,
        'username' => $request->username,
        'password' => Hash::make($request->password),
        'role_id' => 2, // Asumsi: Semua yang register adalah Petugas (role_id = 2)
    ]);

    event(new Registered($user));

    Auth::login($user);

    return redirect(RouteServiceProvider::HOME);
}
}

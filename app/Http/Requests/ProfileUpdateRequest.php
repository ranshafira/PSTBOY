<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Pastikan user yang login diizinkan update profil.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi profil user.
     */
    public function rules(): array
    {
        return [
            'nama_lengkap' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\s]+$/', // hanya huruf dan spasi
            ],
            'nip' => [
                'required',
                'digits:18', // wajib 18 angka
                Rule::unique('users', 'nip')->ignore($this->user()->id),
            ],
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($this->user()->id),
            ],
            'email' => [
                'nullable',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user()->id),
            ],
            'password' => [
                'nullable', // boleh kosong kalau tidak ingin ubah
                'confirmed',
                Rules\Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(), // harus kombinasi karakter kuat
            ],
            'no_hp' => [
                'nullable',
                'regex:/^[0-9]{10,15}$/', // hanya angka, 10–15 digit
            ],
            'foto' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048', // maksimal 2MB
            ],
        ];
    }

    /**
     * Pesan error yang lebih jelas untuk user.
     */
    public function messages(): array
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
            'nip.required' => 'NIP wajib diisi.',
            'nip.digits' => 'NIP harus terdiri dari 18 angka.',
            'nip.unique' => 'NIP sudah digunakan oleh pengguna lain.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh pengguna lain.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'password.letters' => 'Password harus mengandung huruf.',
            'password.mixedCase' => 'Password harus memiliki huruf besar dan kecil.',
            'password.numbers' => 'Password harus mengandung angka.',
            'password.symbols' => 'Password harus mengandung simbol (misal: @, #, $, !).',
            'no_hp.regex' => 'Nomor HP hanya boleh berisi angka (10–15 digit).',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format gambar harus JPG, JPEG, atau PNG.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
        ];
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kepala
        User::updateOrCreate(
            ['username' => 'kepala-bps'],
            [
                'nama_lengkap' => 'Kepala BPS Boyolali',
                'nip' => '001',
                'email' => 'kepalabps@example.com',
                'no_hp' => '081234567890',
                'password' => Hash::make(env('DEFAULT_KEPALA_PASS', 'kepala123')),
                'role_id' => 3, 
            ]
        );

        // Admin
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'nama_lengkap' => 'Admin Sistem',
                'nip' => '002',
                'email' => 'adminpst@example.com',
                'no_hp' => '081234567891',
                'password' => Hash::make(env('DEFAULT_ADMIN_PASS', 'admin123')),
                'role_id' => 1, 
            ]
        );
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisLayananSeeder extends Seeder
{
    public function run()
    {
        DB::table('jenis_layanan')->insert([
            ['id' => 1, 'nama_layanan' => 'Perpustakaan', 'kode_antrian' => 'A'],
            ['id' => 2, 'nama_layanan' => 'Konsultasi Statistik', 'kode_antrian' => 'B'],
            ['id' => 3, 'nama_layanan' => 'Rekomendasi Statistik', 'kode_antrian' => 'C'],
            ['id' => 4, 'nama_layanan' => 'Pengaduan Layanan', 'kode_antrian' => 'D'],
        ]);
    }
}
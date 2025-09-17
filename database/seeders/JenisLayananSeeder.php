<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisLayananSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jenis_layanan')->updateOrInsert(
            ['id' => 1],
            ['nama_layanan' => 'Perpustakaan', 'kode_antrian' => 'PRP-']
        );

        DB::table('jenis_layanan')->updateOrInsert(
            ['id' => 2],
            ['nama_layanan' => 'Konsultasi Statistik', 'kode_antrian' => 'KST-']
        );

        DB::table('jenis_layanan')->updateOrInsert(
            ['id' => 3],
            ['nama_layanan' => 'Rekomendasi Statistik', 'kode_antrian' => 'RST-']
        );

        DB::table('jenis_layanan')->updateOrInsert(
            ['id' => 4],
            ['nama_layanan' => 'Pengaduan', 'kode_antrian' => 'PLS-']
        );
    }
}

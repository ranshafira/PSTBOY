<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Ubah nama tabel jika sebelumnya masih "buku_tamu_khusus"
        if (Schema::hasTable('buku_tamu_khusus')) {
            Schema::rename('buku_tamu_khusus', 'bukutamu_nonpst');
        }

        // 2. Tambahkan kolom 'tujuan' (enum)
        Schema::table('bukutamu_nonpst', function (Blueprint $table) {
            $table->enum('tujuan', ['Kepala BPS', 'Subbag Umum', 'Bagian Teknis', 'Bagian Pengaduan', 'Lainnya'])
            ->after('nama_tamu');
        });
    }

    public function down(): void
    {
        // Hapus kolom 'tujuan'
        Schema::table('bukutamu_nonpst', function (Blueprint $table) {
            $table->dropColumn('tujuan');
        });

        // Kembalikan nama tabel lama jika dibutuhkan
        if (Schema::hasTable('bukutamu_nonpst')) {
            Schema::rename('bukutamu_nonpst', 'buku_tamu_khusus');
        }
    }
};

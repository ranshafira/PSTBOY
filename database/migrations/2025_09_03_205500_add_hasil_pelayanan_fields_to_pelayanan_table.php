<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // --- LANGKAH 1: Ganti nama kolom terlebih dahulu ---
        Schema::table('pelayanan', function (Blueprint $table) {
            $table->renameColumn('hasil_pelayanan', 'deskripsi_hasil');
        });

        // --- LANGKAH 2: Setelah nama diubah, baru tambahkan kolom-kolom baru ---
        Schema::table('pelayanan', function (Blueprint $table) {
            $table->string('status_penyelesaian')->nullable()->after('kebutuhan_pelanggan');
            $table->json('jenis_output')->nullable()->after('deskripsi_hasil'); // Sekarang 'deskripsi_hasil' sudah ada
            $table->string('path_dokumen_hasil')->nullable()->after('jenis_output');
            $table->boolean('perlu_tindak_lanjut')->default(false)->after('path_dokumen_hasil');
            $table->date('tanggal_tindak_lanjut')->nullable()->after('perlu_tindak_lanjut');
            $table->text('catatan_tindak_lanjut')->nullable()->after('tanggal_tindak_lanjut');
            $table->text('catatan_tambahan')->nullable()->after('catatan_tindak_lanjut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Lakukan kebalikannya: Hapus kolom baru dulu
        Schema::table('pelayanan', function (Blueprint $table) {
            $table->dropColumn([
                'status_penyelesaian',
                'jenis_output',
                'path_dokumen_hasil',
                'perlu_tindak_lanjut',
                'tanggal_tindak_lanjut',
                'catatan_tindak_lanjut',
                'catatan_tambahan'
            ]);
        });
        
        // Baru ganti namanya kembali
        Schema::table('pelayanan', function (Blueprint $table) {
            $table->renameColumn('deskripsi_hasil', 'hasil_pelayanan');
        });
    }
};
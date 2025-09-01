<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pelayanan', function (Blueprint $table) {
            // Menambahkan kolom foreign key ke tabel jenis_layanan
            $table->foreignId('jenis_layanan_id')
                  ->nullable()
                  ->after('antrian_id') // Agar posisinya rapi setelah antrian_id
                  ->constrained('jenis_layanan')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('pelayanan', function (Blueprint $table) {
            $table->dropForeign(['jenis_layanan_id']);
            $table->dropColumn('jenis_layanan_id');
        });
    }
};
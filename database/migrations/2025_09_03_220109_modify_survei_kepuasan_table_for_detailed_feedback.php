<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('survei_kepuasan', function (Blueprint $table) {
            // Ubah tipe kolom agar bisa menyimpan banyak data skor
            $table->json('skor_kepuasan')->change();
            // Ubah tipe kolom agar bisa menyimpan 2 jenis masukan
            $table->json('saran_masukan')->change();
            // Tambah kolom baru untuk pertanyaan rekomendasi
            $table->boolean('rekomendasi')->nullable()->after('skor_kepuasan');
        });
    }

    public function down(): void
    {
        Schema::table('survei_kepuasan', function (Blueprint $table) {
            // Kembalikan ke tipe data semula jika di-rollback
            $table->integer('skor_kepuasan')->change();
            $table->text('saran_masukan')->change();
            $table->dropColumn('rekomendasi');
        });
    }
};
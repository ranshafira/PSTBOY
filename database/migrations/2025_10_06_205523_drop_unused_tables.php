<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 🧹 Hapus tabel-tabel yang tidak digunakan
        Schema::dropIfExists('survey_skds');
        Schema::dropIfExists('survey_kepuasan');
        Schema::dropIfExists('personal_access_tokens');
    }

    public function down(): void
    {
        // 🔁 Buat ulang tabel-tabel sesuai struktur sebelumnya

        Schema::create('survey_skds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelayanan_id')->constrained('pelayanan')->onDelete('cascade');
            $table->integer('skor_pertanyaan_1')->nullable();
            $table->integer('skor_pertanyaan_2')->nullable();
            $table->text('jawaban_terbuka')->nullable();
            $table->timestamps();
        });

        Schema::create('survey_kepuasan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelayanan_id')->constrained('pelayanan')->onDelete('cascade');
            $table->json('skor_kepuasan')->nullable();
            $table->json('rekomendasi')->nullable();
            $table->json('saran_masukan')->nullable();
            $table->timestamp('waktu_isi')->nullable();
        });

        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }
};

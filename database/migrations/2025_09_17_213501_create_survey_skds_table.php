<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_skds', function (Blueprint $table) {
            $table->id();
            // PASTIKAN BARIS INI BENAR (tanpa 's' di akhir 'pelayanan')
            $table->foreignId('pelayanan_id')->constrained('pelayanan')->onDelete('cascade');

            $table->tinyInteger('skor_pertanyaan_1')->nullable();
            $table->tinyInteger('skor_pertanyaan_2')->nullable();
            $table->text('jawaban_terbuka')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_skds');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('survei_kepuasan', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pelayanan_id')->unique()->constrained('pelayanan')->onDelete('cascade');
        $table->tinyInteger('skor_kepuasan');
        $table->text('saran_masukan')->nullable();
        $table->timestamp('waktu_isi')->useCurrent();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survei_kepuasan');
    }
};

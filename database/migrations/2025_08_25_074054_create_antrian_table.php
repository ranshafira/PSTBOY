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
    Schema::create('antrian', function (Blueprint $table) {
        $table->id();
        $table->string('nomor_antrian', 10);
        $table->foreignId('jenis_layanan_id')->constrained('jenis_layanan');
        $table->timestamp('waktu_ambil')->useCurrent();
        $table->enum('status', ['menunggu', 'dipanggil', 'selesai', 'dilewati'])->default('menunggu');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antrian');
    }
};

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
    Schema::create('pelayanan', function (Blueprint $table) {
        $table->id();
        $table->foreignId('petugas_id')->constrained('users');
        $table->foreignId('antrian_id')->constrained('antrian');
        $table->string('nama_pelanggan');
        $table->string('instansi_pelanggan')->nullable();
        $table->string('kontak_pelanggan', 100)->nullable();
        $table->string('path_surat_pengantar')->nullable();
        $table->text('kebutuhan_pelanggan');
        $table->text('hasil_pelayanan');
        $table->timestamp('waktu_mulai_sesi');
        $table->timestamp('waktu_selesai_sesi');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelayanan');
    }
};

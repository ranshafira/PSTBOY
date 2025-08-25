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
    Schema::create('buku_tamu_khusus', function (Blueprint $table) {
        $table->id();
        $table->string('nama_tamu');
        $table->string('instansi_tamu')->nullable();
        $table->string('kontak_tamu', 100)->nullable();
        $table->text('keperluan');
        $table->timestamp('waktu_kunjungan')->useCurrent();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_tamu_khusus');
    }
};

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
    Schema::table('pelayanan', function (Blueprint $table) {
        $table->string('nama_pelanggan')->nullable()->change();
        $table->string('instansi_pelanggan')->nullable()->change();
        $table->string('kontak_pelanggan')->nullable()->change();
        $table->text('kebutuhan_pelanggan')->nullable()->change();
        $table->string('path_surat_pengantar')->nullable()->change();
        $table->text('hasil_pelayanan')->nullable()->change();
        $table->dateTime('waktu_selesai_sesi')->nullable()->change();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelayanan', function (Blueprint $table) {
            //
        });
    }
};

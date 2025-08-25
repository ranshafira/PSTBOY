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
    Schema::create('presensi', function (Blueprint $table) {
        $table->foreignId('petugas_id')->constrained('users')->onDelete('cascade');
        $table->date('tanggal');
        $table->time('waktu_datang');
        $table->time('waktu_pulang')->nullable();

        // Ini untuk Composite Primary Key
        $table->primary(['petugas_id', 'tanggal']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi');
    }
};

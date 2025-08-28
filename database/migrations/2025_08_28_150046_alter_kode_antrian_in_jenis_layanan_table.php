<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jenis_layanan', function (Blueprint $table) {
            $table->char('kode_antrian', 4)->change();
        });
    }

    public function down(): void
    {
        Schema::table('jenis_layanan', function (Blueprint $table) {
            $table->char('kode_antrian', 1)->change();
        });
    }
};

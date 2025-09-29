<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateJenisLayananIdNullableInAntrianTable extends Migration
{
    public function up()
    {
        Schema::table('antrian', function (Blueprint $table) {
            $table->unsignedBigInteger('jenis_layanan_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('antrian', function (Blueprint $table) {
            $table->unsignedBigInteger('jenis_layanan_id')->nullable(false)->change();
        });
    }
}

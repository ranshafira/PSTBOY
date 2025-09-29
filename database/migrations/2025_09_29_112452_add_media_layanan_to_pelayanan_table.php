<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pelayanan', function (Blueprint $table) {
            $table->enum('media_layanan', ['langsung', 'whatsapp', 'email'])->after('pendidikan');
        });
    }

    public function down()
    {
        Schema::table('pelayanan', function (Blueprint $table) {
            $table->dropColumn('media_layanan');
        });
    }

};

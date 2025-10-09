<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pelayanan', function (Blueprint $table) {
            $table->string('kode_unik')->unique()->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('pelayanan', function (Blueprint $table) {
            $table->dropColumn('kode_unik');
        });
    }
};

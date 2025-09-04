<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    DB::statement("ALTER TABLE antrian MODIFY status ENUM('menunggu', 'dipanggil', 'sedang_dilayani', 'dilewati', 'selesai') NOT NULL DEFAULT 'menunggu'");
}

public function down()
{
    DB::statement("ALTER TABLE antrian MODIFY status ENUM('menunggu', 'dipanggil', 'dilewati', 'selesai') NOT NULL DEFAULT 'menunggu'");
}

};

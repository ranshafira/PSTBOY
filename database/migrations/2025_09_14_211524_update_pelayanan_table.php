<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pelayanan', function (Blueprint $table) {
            // rename dulu
            $table->renameColumn('kontak_pelanggan', 'no_hp');
        });

        Schema::table('pelayanan', function (Blueprint $table) {
            // baru tambah kolom lain
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable()->after('antrian_id');
            $table->string('pendidikan')->nullable()->after('jenis_kelamin');
            $table->string('email')->nullable()->after('no_hp');

            // rename semua pelanggan -> pengunjung
            $table->renameColumn('nama_pelanggan', 'nama_pengunjung');
            $table->renameColumn('instansi_pelanggan', 'instansi_pengunjung');
            $table->renameColumn('kebutuhan_pelanggan', 'kebutuhan_pengunjung');
        });
    }

    public function down(): void
    {
        Schema::table('pelayanan', function (Blueprint $table) {
            $table->dropColumn(['jenis_kelamin', 'pendidikan', 'email']);
            $table->renameColumn('no_hp', 'kontak_pelanggan');
            $table->renameColumn('nama_pengunjung', 'nama_pelanggan');
            $table->renameColumn('instansi_pengunjung', 'instansi_pelanggan');
            $table->renameColumn('kebutuhan_pengunjung', 'kebutuhan_pelanggan');
        });
    }
};

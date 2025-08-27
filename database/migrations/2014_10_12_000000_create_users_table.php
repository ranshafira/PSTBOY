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
        Schema::create('users', function (Blueprint $table) {
            // Kolom standar Laravel
            $table->id();

            // Kolom kustom Anda menggantikan kolom bawaan
            $table->string('nama_lengkap');
            $table->string('nip', 18)->unique();
            $table->string('username', 100)->unique();
            
            // Kolom standar Laravel
            $table->string('password');

            // Relasi ke tabel roles
            $table->foreignId('role_id')->constrained('roles');
            
            // Kolom standar Laravel yang penting
            $table->rememberToken(); // Untuk fitur "remember me"
            $table->timestamps(); // Otomatis membuat created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('shift', ['pagi', 'siang']);
            $table->timestamps();

            $table->unique(['user_id', 'tanggal', 'shift']); // Satu user hanya boleh satu shift per hari
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};

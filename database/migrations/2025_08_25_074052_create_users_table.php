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
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('nip', 18)->unique();
        $table->string('nama_lengkap');
        $table->string('username', 100)->unique();
        $table->string('password');
        $table->foreignId('role_id')->constrained('roles');
        $table->timestamp('created_at')->useCurrent();
        // Laravel biasanya menggunakan $table->timestamps(); 
        // tapi kita sesuaikan dengan SQL awal Anda.
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

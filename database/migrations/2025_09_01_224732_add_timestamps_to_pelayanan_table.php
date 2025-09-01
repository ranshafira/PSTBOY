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
    Schema::table('pelayanan', function (Blueprint $table) {
        $table->timestamps(); // Cukup 1 baris ini!
    });
}

    /**
     * Reverse the migrations.
     */
   public function down(): void
{
    Schema::table('pelayanan', function (Blueprint $table) {
        $table->dropTimestamps(); // 1 baris ini akan menghapus created_at & updated_at
    });
}
};

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pelayanan', function (Blueprint $table) {
            // Kolom untuk menyimpan PIN survei (misal: "A6B-3F9")
            $table->string('survey_token')->nullable()->unique()->after('waktu_selesai_sesi');
            // Kolom untuk menandai waktu survei selesai diisi
            $table->timestamp('survey_completed_at')->nullable()->after('survey_token');
        });
    }

    public function down(): void
    {
        Schema::table('pelayanan', function (Blueprint $table) {
            $table->dropColumn(['survey_token', 'survey_completed_at']);
        });
    }
};
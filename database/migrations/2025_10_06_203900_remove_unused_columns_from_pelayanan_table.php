<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pelayanan', function (Blueprint $table) {
            $table->dropColumn(['catatan_tambahan', 'survey_token', 'skd_token', 'survey_completed_at']);
        });
    }

    public function down(): void
    {
        Schema::table('pelayanan', function (Blueprint $table) {
            $table->text('catatan_tambahan')->nullable();
            $table->string('survey_token')->nullable();
            $table->string('skd_token')->nullable();
            $table->timestamp('survey_completed_at')->nullable();
        });
    }
};

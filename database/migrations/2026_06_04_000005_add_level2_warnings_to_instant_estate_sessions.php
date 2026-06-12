<?php
// database/migrations/2026_06_04_000005_add_level2_validation_columns_to_instant_estate_sessions.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('instant_estate_sessions', function (Blueprint $table) {
            // Add level2_validation_errors if it doesn't exist
            if (!Schema::hasColumn('instant_estate_sessions', 'level2_validation_errors')) {
                $table->json('level2_validation_errors')->nullable();
            }
            
            // Add level2_validation_warnings if it doesn't exist
            if (!Schema::hasColumn('instant_estate_sessions', 'level2_validation_warnings')) {
                $table->json('level2_validation_warnings')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('instant_estate_sessions', function (Blueprint $table) {
            if (Schema::hasColumn('instant_estate_sessions', 'level2_validation_errors')) {
                $table->dropColumn('level2_validation_errors');
            }
            if (Schema::hasColumn('instant_estate_sessions', 'level2_validation_warnings')) {
                $table->dropColumn('level2_validation_warnings');
            }
        });
    }
};
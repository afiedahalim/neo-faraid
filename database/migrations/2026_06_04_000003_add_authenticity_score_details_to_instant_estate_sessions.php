<?php
// database/migrations/2026_06_04_000003_add_authenticity_score_details_to_instant_estate_sessions.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('instant_estate_sessions', function (Blueprint $table) {
            if (!Schema::hasColumn('instant_estate_sessions', 'authenticity_score_details')) {
                $table->json('authenticity_score_details')->nullable()->after('authenticity_score')
                    ->comment('Detailed breakdown of authenticity score features');
            }
        });
    }

    public function down()
    {
        Schema::table('instant_estate_sessions', function (Blueprint $table) {
            if (Schema::hasColumn('instant_estate_sessions', 'authenticity_score_details')) {
                $table->dropColumn('authenticity_score_details');
            }
        });
    }
};
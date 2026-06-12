<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('calculations', function (Blueprint $table) {
            // Add the missing column
            $table->string('calculation_method')->default('local')->after('tree_generation_status');
            
            // Also add other missing columns if needed
            if (!Schema::hasColumn('calculations', 'faraid_scenario_id')) {
                $table->unsignedBigInteger('faraid_scenario_id')->nullable()->after('scenario_number');
            }
            
            if (!Schema::hasColumn('calculations', 'scenario_rules_applied')) {
                $table->json('scenario_rules_applied')->nullable()->after('calculation_method');
            }
            
            if (!Schema::hasColumn('calculations', 'eligible_heirs_count')) {
                $table->integer('eligible_heirs_count')->default(0)->after('total_heirs');
            }
        });
    }

    public function down()
    {
        Schema::table('calculations', function (Blueprint $table) {
            $table->dropColumn(['calculation_method', 'scenario_rules_applied', 'eligible_heirs_count']);
            
            if (Schema::hasColumn('calculations', 'faraid_scenario_id')) {
                $table->dropColumn('faraid_scenario_id');
            }
        });
    }
};
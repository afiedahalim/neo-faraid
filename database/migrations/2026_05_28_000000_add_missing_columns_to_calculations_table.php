<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('calculations', function (Blueprint $table) {
            // Add missing JSON columns that the controller uses
            if (!Schema::hasColumn('calculations', 'scenario_data')) {
                $table->json('scenario_data')->nullable()->after('calculation_data');
            }
            if (!Schema::hasColumn('calculations', 'chart_data')) {
                $table->json('chart_data')->nullable()->after('scenario_data');
            }
            if (!Schema::hasColumn('calculations', 'tree_data')) {
                $table->json('tree_data')->nullable()->after('chart_data');
            }
            // Ensure eligible_heirs_count exists (added earlier but double-check)
            if (!Schema::hasColumn('calculations', 'eligible_heirs_count')) {
                $table->integer('eligible_heirs_count')->default(0)->after('total_heirs');
            }
            // Ensure calculation_method exists
            if (!Schema::hasColumn('calculations', 'calculation_method')) {
                $table->string('calculation_method')->default('local')->after('tree_generation_status');
            }
            // Ensure scenario_rules_applied exists
            if (!Schema::hasColumn('calculations', 'scenario_rules_applied')) {
                $table->json('scenario_rules_applied')->nullable()->after('calculation_method');
            }
            // Ensure faraid_scenario_id exists
            if (!Schema::hasColumn('calculations', 'faraid_scenario_id')) {
                $table->unsignedBigInteger('faraid_scenario_id')->nullable()->after('scenario_number');
            }
        });
    }

    public function down()
    {
        Schema::table('calculations', function (Blueprint $table) {
            $table->dropColumn([
                'scenario_data', 'chart_data', 'tree_data',
                'eligible_heirs_count', 'calculation_method',
                'scenario_rules_applied', 'faraid_scenario_id'
            ]);
        });
    }
};
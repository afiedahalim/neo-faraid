<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('calculations', function (Blueprint $table) {
            // Deceased & contact details – no AFTER clauses
            if (!Schema::hasColumn('calculations', 'deceased_nric')) {
                $table->string('deceased_nric', 20)->nullable();
            }
            if (!Schema::hasColumn('calculations', 'cause_of_death')) {
                $table->string('cause_of_death', 255)->nullable();
            }
            if (!Schema::hasColumn('calculations', 'death_place')) {
                $table->string('death_place', 255)->nullable();
            }
            if (!Schema::hasColumn('calculations', 'contact_email')) {
                $table->string('contact_email', 255)->nullable();
            }
            if (!Schema::hasColumn('calculations', 'contact_phone')) {
                $table->string('contact_phone', 20)->nullable();
            }
            if (!Schema::hasColumn('calculations', 'residential_address')) {
                $table->text('residential_address')->nullable();
            }

            // Grandparents status
            if (!Schema::hasColumn('calculations', 'fathers_father_status')) {
                $table->enum('fathers_father_status', ['alive', 'deceased'])->default('deceased');
            }
            if (!Schema::hasColumn('calculations', 'fathers_mother_status')) {
                $table->enum('fathers_mother_status', ['alive', 'deceased'])->default('deceased');
            }
            if (!Schema::hasColumn('calculations', 'mothers_mother_status')) {
                $table->enum('mothers_mother_status', ['alive', 'deceased'])->default('deceased');
            }

            // Maternal siblings
            if (!Schema::hasColumn('calculations', 'maternal_brother_count')) {
                $table->integer('maternal_brother_count')->default(0);
            }
            if (!Schema::hasColumn('calculations', 'maternal_sister_count')) {
                $table->integer('maternal_sister_count')->default(0);
            }

            // Tree generation fields
            if (!Schema::hasColumn('calculations', 'tree_generation_status')) {
                $table->string('tree_generation_status')->default('pending');
            }
            if (!Schema::hasColumn('calculations', 'tree_generation_attempted_at')) {
                $table->timestamp('tree_generation_attempted_at')->nullable();
            }
            if (!Schema::hasColumn('calculations', 'family_tree_image')) {
                $table->string('family_tree_image')->nullable();
            }
            if (!Schema::hasColumn('calculations', 'tree_generated_at')) {
                $table->timestamp('tree_generated_at')->nullable();
            }
            if (!Schema::hasColumn('calculations', 'tree_generation_error')) {
                $table->text('tree_generation_error')->nullable();
            }

            // JSON data fields
            if (!Schema::hasColumn('calculations', 'scenario_data')) {
                $table->json('scenario_data')->nullable();
            }
            if (!Schema::hasColumn('calculations', 'chart_data')) {
                $table->json('chart_data')->nullable();
            }
            if (!Schema::hasColumn('calculations', 'tree_data')) {
                $table->json('tree_data')->nullable();
            }

            // Additional columns from previous migrations (safe to re-run)
            if (!Schema::hasColumn('calculations', 'eligible_heirs_count')) {
                $table->integer('eligible_heirs_count')->default(0);
            }
            if (!Schema::hasColumn('calculations', 'faraid_scenario_id')) {
                $table->unsignedBigInteger('faraid_scenario_id')->nullable();
            }
            if (!Schema::hasColumn('calculations', 'calculation_method')) {
                $table->string('calculation_method')->default('local');
            }
            if (!Schema::hasColumn('calculations', 'scenario_rules_applied')) {
                $table->json('scenario_rules_applied')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('calculations', function (Blueprint $table) {
            $columns = [
                'deceased_nric', 'cause_of_death', 'death_place', 'contact_email', 'contact_phone',
                'residential_address', 'fathers_father_status', 'fathers_mother_status', 'mothers_mother_status',
                'maternal_brother_count', 'maternal_sister_count', 'tree_generation_status',
                'tree_generation_attempted_at', 'family_tree_image', 'tree_generated_at',
                'tree_generation_error', 'scenario_data', 'chart_data', 'tree_data',
                'eligible_heirs_count', 'faraid_scenario_id', 'calculation_method', 'scenario_rules_applied'
            ];
            foreach ($columns as $col) {
                if (Schema::hasColumn('calculations', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
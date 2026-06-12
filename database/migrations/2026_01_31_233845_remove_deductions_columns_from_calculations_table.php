<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('calculations', function (Blueprint $table) {
            // Remove the deduction columns
            $table->dropColumn(['debts', 'funeral_costs', 'wasiyyah']);
            
            // Also remove other unused columns if you want
            $table->dropColumn(['grandson_count', 'granddaughter_count', 'paternal_grandfather_status', 
                                'paternal_grandmother_count', 'maternal_grandmother_count', 
                                'shares_summary', 'final_distribution', 'deleted_at']);
        });
    }

    public function down()
    {
        Schema::table('calculations', function (Blueprint $table) {
            // Add columns back if rollback
            $table->decimal('debts', 15, 2)->default(0)->after('total_assets');
            $table->decimal('funeral_costs', 15, 2)->default(0)->after('debts');
            $table->decimal('wasiyyah', 15, 2)->default(0)->after('funeral_costs');
            
            $table->integer('grandson_count')->default(0)->after('daughter_count');
            $table->integer('granddaughter_count')->default(0)->after('grandson_count');
            $table->string('paternal_grandfather_status', 10)->default('deceased')->after('maternal_sister_count');
            $table->integer('paternal_grandmother_count')->default(0)->after('paternal_grandfather_status');
            $table->integer('maternal_grandmother_count')->default(0)->after('paternal_grandmother_count');
            $table->json('shares_summary')->nullable()->after('distribution_summary');
            $table->json('final_distribution')->nullable()->after('shares_summary');
            $table->softDeletes()->after('updated_at');
        });
    }
};
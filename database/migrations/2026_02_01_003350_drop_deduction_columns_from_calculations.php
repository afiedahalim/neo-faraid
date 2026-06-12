<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('calculations', function (Blueprint $table) {
            // Check if columns exist before dropping them
            if (Schema::hasColumn('calculations', 'debts')) {
                $table->dropColumn('debts');
            }
            if (Schema::hasColumn('calculations', 'funeral_costs')) {
                $table->dropColumn('funeral_costs');
            }
            if (Schema::hasColumn('calculations', 'wasiyyah')) {
                $table->dropColumn('wasiyyah');
            }
        });
    }

    public function down()
    {
        Schema::table('calculations', function (Blueprint $table) {
            $table->decimal('debts', 15, 2)->default(0);
            $table->decimal('funeral_costs', 15, 2)->default(0);
            $table->decimal('wasiyyah', 15, 2)->default(0);
        });
    }
};
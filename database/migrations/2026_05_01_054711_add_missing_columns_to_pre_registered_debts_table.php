<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pre_registered_debts', function (Blueprint $table) {
            if (!Schema::hasColumn('pre_registered_debts', 'debt_type')) {
                $table->string('debt_type', 100)->nullable()->after('creditor_contact');
            }
            if (!Schema::hasColumn('pre_registered_debts', 'type')) {
                $table->string('type', 50)->nullable()->after('amount');
            }
            if (!Schema::hasColumn('pre_registered_debts', 'category')) {
                $table->string('category', 100)->nullable()->after('type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pre_registered_debts', function (Blueprint $table) {
            if (Schema::hasColumn('pre_registered_debts', 'debt_type')) {
                $table->dropColumn('debt_type');
            }
            if (Schema::hasColumn('pre_registered_debts', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('pre_registered_debts', 'category')) {
                $table->dropColumn('category');
            }
        });
    }
};
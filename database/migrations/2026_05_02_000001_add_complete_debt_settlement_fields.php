<?php
// database/migrations/2026_05_02_000001_add_complete_debt_settlement_fields.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pre_registered_debts', function (Blueprint $table) {
            if (!Schema::hasColumn('pre_registered_debts', 'amount_paid')) {
                $table->decimal('amount_paid', 15, 2)->default(0)->after('amount');
            }
            
            // Use generated column for remaining_amount (MySQL 5.7+)
            if (!Schema::hasColumn('pre_registered_debts', 'remaining_amount')) {
                $table->decimal('remaining_amount', 15, 2)->virtualAs('amount - amount_paid')->after('amount_paid');
            }
            
            if (!Schema::hasColumn('pre_registered_debts', 'status')) {
                $table->enum('status', ['pending', 'partial', 'settled'])->default('pending')->after('remaining_amount');
            }
            
            if (!Schema::hasColumn('pre_registered_debts', 'settled_at')) {
                $table->timestamp('settled_at')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('pre_registered_debts', 'settlement_reference')) {
                $table->string('settlement_reference')->nullable()->after('settled_at');
            }
            
            if (!Schema::hasColumn('pre_registered_debts', 'payment_history')) {
                $table->json('payment_history')->nullable()->after('settlement_reference');
            }

            // SHORT INDEX NAMES
            $table->index('status', 'debt_status_idx');
            $table->index('settled_at', 'debt_settled_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pre_registered_debts', function (Blueprint $table) {
            $columns = ['amount_paid', 'remaining_amount', 'status', 'settled_at', 'settlement_reference', 'payment_history'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('pre_registered_debts', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
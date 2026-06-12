<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if table already exists
        if (Schema::hasTable('estate_debts')) {
            Log::info("Table 'estate_debts' already exists. Skipping creation.");
            return;
        }

        Schema::create('estate_debts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instant_estate_session_id')
                ->constrained('instant_estate_sessions')
                ->onDelete('cascade')
                ->comment('Reference to the instant estate session');
            $table->string('creditor_name')
                ->comment('Name of the creditor (bank, person, institution)');
            $table->decimal('amount', 15, 2)->default(0)
                ->comment('Original debt amount in RM');
            $table->decimal('amount_paid', 15, 2)->default(0)
                ->comment('Amount already paid towards this debt');
            $table->text('description')->nullable()
                ->comment('Description or additional details about this debt');
            $table->string('status')->default('pending')
                ->comment('Status: pending, partial, paid');
            $table->date('due_date')->nullable()
                ->comment('Due date for the debt payment');
            $table->timestamp('paid_at')->nullable()
                ->comment('Timestamp when debt was fully paid');
            $table->string('reference_number')->nullable()
                ->comment('Reference number, account number, or loan ID');
            $table->string('payment_method')->nullable()
                ->comment('Method of payment used');
            $table->json('payment_history')->nullable()
                ->comment('JSON array of payment records');
            $table->text('notes')->nullable()
                ->comment('Additional notes');
            $table->timestamps();

            // Indexes
            $table->index('instant_estate_session_id', 'estate_debts_session_idx');
            $table->index('status', 'estate_debts_status_idx');
            $table->index('creditor_name', 'estate_debts_creditor_idx');
        });

        Log::info("Table 'estate_debts' created successfully.");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estate_debts');
        Log::info("Table 'estate_debts' dropped.");
    }
};
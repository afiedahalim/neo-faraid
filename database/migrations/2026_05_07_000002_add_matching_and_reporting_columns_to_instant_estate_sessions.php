<?php

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
        Schema::table('instant_estate_sessions', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('instant_estate_sessions', 'matched_record_id')) {
                $table->string('matched_record_id')->nullable()->after('notification_request_id');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'matched_record_type')) {
                $table->string('matched_record_type')->nullable()->after('matched_record_id');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'report_data')) {
                $table->json('report_data')->nullable()->after('matched_record_type');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'report_generated_at')) {
                $table->timestamp('report_generated_at')->nullable()->after('report_data');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'recipient_email')) {
                $table->string('recipient_email')->nullable()->after('report_generated_at');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'email_sent_at')) {
                $table->timestamp('email_sent_at')->nullable()->after('recipient_email');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'processing_attempts')) {
                $table->integer('processing_attempts')->default(0)->after('processing_time_ms');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'last_processing_attempt_at')) {
                $table->timestamp('last_processing_attempt_at')->nullable()->after('processing_attempts');
            }
        });

        // Add indexes
        Schema::table('instant_estate_sessions', function (Blueprint $table) {
            if (!Schema::hasIndex('instant_estate_sessions', 'idx_matched_record_id')) {
                $table->index('matched_record_id', 'idx_matched_record_id');
            }
            
            if (!Schema::hasIndex('instant_estate_sessions', 'idx_matched_record_type')) {
                $table->index('matched_record_type', 'idx_matched_record_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('instant_estate_sessions', function (Blueprint $table) {
            $columns = [
                'matched_record_id',
                'matched_record_type',
                'report_data',
                'report_generated_at',
                'recipient_email',
                'email_sent_at',
                'processing_attempts',
                'last_processing_attempt_at',
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('instant_estate_sessions', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
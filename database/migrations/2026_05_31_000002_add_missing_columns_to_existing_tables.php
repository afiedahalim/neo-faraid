<?php
// database/migrations/xxxx_xx_xx_000002_add_missing_columns_to_existing_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToExistingTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add missing columns to instant_estate_sessions
        if (Schema::hasTable('instant_estate_sessions')) {
            Schema::table('instant_estate_sessions', function (Blueprint $table) {
                if (!Schema::hasColumn('instant_estate_sessions', 'notification_request_id')) {
                    $table->unsignedBigInteger('notification_request_id')->nullable()->after('notification_requested_at');
                }
                if (!Schema::hasColumn('instant_estate_sessions', 'recipient_email')) {
                    $table->string('recipient_email')->nullable()->after('notification_request_id');
                }
                if (!Schema::hasColumn('instant_estate_sessions', 'email_sent_at')) {
                    $table->timestamp('email_sent_at')->nullable()->after('recipient_email');
                }
                if (!Schema::hasColumn('instant_estate_sessions', 'matched_record_type')) {
                    $table->string('matched_record_type')->nullable()->after('matched_record_id');
                }
            });
        }
        
        // Add missing columns to notification_requests
        if (Schema::hasTable('notification_requests')) {
            Schema::table('notification_requests', function (Blueprint $table) {
                if (!Schema::hasColumn('notification_requests', 'pdf_content')) {
                    $table->longText('pdf_content')->nullable()->after('email_results');
                }
                if (!Schema::hasColumn('notification_requests', 'pdf_path')) {
                    $table->string('pdf_path')->nullable()->after('pdf_content');
                }
                if (!Schema::hasColumn('notification_requests', 'sent_by')) {
                    $table->unsignedBigInteger('sent_by')->nullable()->after('pdf_path');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Reversal not needed for production
    }
}
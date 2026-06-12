<?php
// database/migrations/2026_06_01_000000_fix_estate_notifications_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixEstateNotificationsTable extends Migration
{
    public function up()
    {
        // First check if table exists
        if (!Schema::hasTable('estate_notifications')) {
            // Create the table if it doesn't exist
            Schema::create('estate_notifications', function (Blueprint $table) {
                $table->id();
                $table->string('notification_token', 100)->unique();
                $table->unsignedBigInteger('instant_estate_session_id')->nullable();
                $table->unsignedBigInteger('estate_pre_registration_id')->nullable();
                $table->string('beneficiary_email')->nullable();
                $table->string('beneficiary_name')->nullable();
                $table->string('beneficiary_type')->nullable();
                $table->string('beneficiary_relationship')->nullable();
                $table->string('access_token', 100)->nullable();
                $table->enum('status', ['pending', 'sent', 'viewed', 'expired', 'revoked'])->default('pending');
                $table->timestamp('expires_at')->nullable();
                $table->integer('access_count')->default(0);
                $table->timestamp('last_accessed_at')->nullable();
                $table->string('last_accessed_ip')->nullable();
                $table->string('last_accessed_user_agent')->nullable();
                $table->json('accessed_ips')->nullable();
                $table->json('user_agents')->nullable();
                $table->timestamp('first_viewed_at')->nullable();
                $table->boolean('requires_email_verification')->default(false);
                $table->string('verification_code', 10)->nullable();
                $table->integer('max_access_count')->default(5);
                $table->timestamp('notification_sent_at')->nullable();
                $table->timestamps();
                
                $table->index('notification_token');
                $table->index('access_token');
                $table->index('beneficiary_email');
                $table->index(['estate_pre_registration_id', 'beneficiary_type']);
            });
            return;
        }

        // If table exists, add missing columns without referencing non-existent columns
        $columns = Schema::getColumnListing('estate_notifications');
        
        // Add notification_token
        if (!in_array('notification_token', $columns)) {
            Schema::table('estate_notifications', function (Blueprint $table) {
                $table->string('notification_token', 100)->nullable()->unique()->after('id');
            });
        }
        
        // Add beneficiary columns
        if (!in_array('beneficiary_email', $columns)) {
            Schema::table('estate_notifications', function (Blueprint $table) {
                $table->string('beneficiary_email')->nullable()->after('estate_pre_registration_id');
                $table->string('beneficiary_name')->nullable();
                $table->string('beneficiary_type')->nullable();
                $table->string('beneficiary_relationship')->nullable();
            });
        }
        
        // Add tracking columns (no AFTER clause to avoid errors)
        if (!in_array('last_accessed_ip', $columns)) {
            Schema::table('estate_notifications', function (Blueprint $table) {
                $table->string('last_accessed_ip')->nullable();
                $table->string('last_accessed_user_agent')->nullable();
                $table->json('accessed_ips')->nullable();
                $table->json('user_agents')->nullable();
                $table->timestamp('first_viewed_at')->nullable();
            });
        }
        
        // Add verification columns
        if (!in_array('requires_email_verification', $columns)) {
            Schema::table('estate_notifications', function (Blueprint $table) {
                $table->boolean('requires_email_verification')->default(false);
                $table->string('verification_code', 10)->nullable();
                $table->integer('max_access_count')->default(5);
            });
        }
        
        // Add indexes
        try {
            DB::statement('ALTER TABLE `estate_notifications` ADD INDEX IF NOT EXISTS `idx_notification_token` (`notification_token`)');
        } catch (\Exception $e) {}
        
        try {
            DB::statement('ALTER TABLE `estate_notifications` ADD INDEX IF NOT EXISTS `idx_beneficiary_email` (`beneficiary_email`)');
        } catch (\Exception $e) {}
    }

    public function down()
    {
        // No reversal - keep data
    }
}
<?php
// database/migrations/2026_06_01_000001_fix_all_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixAllTables extends Migration
{
    public function up()
    {
        // ==================== FIX ESTATE NOTIFICATIONS TABLE ====================
        if (Schema::hasTable('estate_notifications')) {
            $columns = Schema::getColumnListing('estate_notifications');
            
            // Add missing columns to estate_notifications
            if (!in_array('notification_token', $columns)) {
                Schema::table('estate_notifications', function (Blueprint $table) {
                    $table->string('notification_token', 100)->nullable()->unique()->after('id');
                });
            }
            
            if (!in_array('beneficiary_email', $columns)) {
                Schema::table('estate_notifications', function (Blueprint $table) {
                    $table->string('beneficiary_email')->nullable()->after('estate_pre_registration_id');
                    $table->string('beneficiary_name')->nullable();
                    $table->string('beneficiary_type')->nullable();
                    $table->string('beneficiary_relationship')->nullable();
                });
            }
            
            if (!in_array('last_accessed_ip', $columns)) {
                Schema::table('estate_notifications', function (Blueprint $table) {
                    $table->string('last_accessed_ip')->nullable();
                    $table->string('last_accessed_user_agent')->nullable();
                    $table->json('accessed_ips')->nullable();
                    $table->json('user_agents')->nullable();
                    $table->timestamp('first_viewed_at')->nullable();
                });
            }
            
            if (!in_array('requires_email_verification', $columns)) {
                Schema::table('estate_notifications', function (Blueprint $table) {
                    $table->boolean('requires_email_verification')->default(false);
                    $table->string('verification_code', 10)->nullable();
                    $table->integer('max_access_count')->default(5);
                });
            }
        }

        // ==================== FIX BENEFICIARY ACCESS LINKS TABLE ====================
        if (Schema::hasTable('beneficiary_access_links')) {
            $columns = Schema::getColumnListing('beneficiary_access_links');
            
            // Add missing columns to beneficiary_access_links
            if (!in_array('last_accessed_ip', $columns)) {
                Schema::table('beneficiary_access_links', function (Blueprint $table) {
                    $table->string('last_accessed_ip')->nullable()->after('last_accessed_at');
                    $table->string('last_accessed_user_agent')->nullable();
                    $table->timestamp('revoked_at')->nullable();
                    $table->string('revoked_reason')->nullable();
                });
            }
            
            if (!in_array('is_active', $columns)) {
                Schema::table('beneficiary_access_links', function (Blueprint $table) {
                    $table->boolean('is_active')->default(true)->after('status');
                });
            }
        }
    }

    public function down()
    {
        // No reversal needed
    }
}
<?php
// database/migrations/2026_05_12_000004_fix_notification_requests_nullable_columns.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notification_requests', function (Blueprint $table) {
            // Make columns nullable that should be nullable
            if (Schema::hasColumn('notification_requests', 'estate_pre_registration_id')) {
                $table->unsignedBigInteger('estate_pre_registration_id')->nullable()->change();
            }
            
            if (Schema::hasColumn('notification_requests', 'instant_estate_session_id')) {
                $table->unsignedBigInteger('instant_estate_session_id')->nullable()->change();
            }
            
            if (Schema::hasColumn('notification_requests', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable()->change();
            }
            
            if (Schema::hasColumn('notification_requests', 'rejected_by')) {
                $table->unsignedBigInteger('rejected_by')->nullable()->change();
            }
            
            if (Schema::hasColumn('notification_requests', 'requested_by')) {
                $table->unsignedBigInteger('requested_by')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('notification_requests', function (Blueprint $table) {
            // Revert changes if needed
            if (Schema::hasColumn('notification_requests', 'estate_pre_registration_id')) {
                $table->unsignedBigInteger('estate_pre_registration_id')->nullable(false)->change();
            }
        });
    }
};
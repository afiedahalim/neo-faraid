<?php
// database/migrations/2026_05_02_000002_create_beneficiary_access_tables.php

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
        // Beneficiary Access Links Table
        Schema::create('beneficiary_access_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estate_pre_registration_id')
                ->constrained('estate_pre_registrations')
                ->onDelete('cascade');
            $table->enum('beneficiary_type', ['heir', 'trustee', 'alternate_trustee', 'wasiyyah'])->default('heir');
            $table->unsignedBigInteger('beneficiary_id')->nullable();
            $table->string('beneficiary_name');
            $table->string('beneficiary_email');
            $table->string('access_token', 128)->unique();
            $table->timestamp('expires_at');
            $table->integer('access_count')->default(0);
            $table->timestamp('last_accessed_at')->nullable();
            $table->enum('status', ['active', 'expired', 'revoked'])->default('active');
            $table->timestamp('notification_sent_at')->nullable();
            $table->timestamps();

            // SHORT INDEX NAMES (under 64 chars)
            $table->index(['estate_pre_registration_id', 'beneficiary_type'], 'bal_estate_type_idx');
            $table->index('access_token', 'bal_token_idx');
            $table->index('expires_at', 'bal_expires_idx');
            $table->index('status', 'bal_status_idx');
            $table->index('beneficiary_email', 'bal_email_idx');
        });

        // Beneficiary Access Logs Table
        Schema::create('beneficiary_access_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beneficiary_access_link_id')
                ->constrained('beneficiary_access_links')
                ->onDelete('cascade');
            $table->timestamp('accessed_at');
            $table->string('accessed_ip', 45)->nullable();
            $table->text('accessed_user_agent')->nullable();
            $table->timestamps();

            // SHORT INDEX NAMES
            $table->index('beneficiary_access_link_id', 'bal_link_id_idx');
            $table->index('accessed_at', 'bal_accessed_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficiary_access_logs');
        Schema::dropIfExists('beneficiary_access_links');
    }
};
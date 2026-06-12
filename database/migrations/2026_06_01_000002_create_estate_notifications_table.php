<?php
// database/migrations/2026_06_01_000002_create_estate_notifications_table.php

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
        Schema::create('estate_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('estate_unique_id')->nullable();
            $table->foreignId('estate_id')->nullable()->constrained('estate_pre_registrations')->onDelete('cascade');
            $table->string('notification_token', 100)->unique();
            $table->string('beneficiary_email')->nullable();
            $table->string('beneficiary_name')->nullable();
            $table->enum('beneficiary_type', ['heir', 'wasiyyah', 'trustee'])->default('heir');
            $table->string('relationship')->nullable();
            $table->decimal('share_percentage', 8, 4)->default(0);
            $table->decimal('share_amount', 15, 2)->default(0);
            $table->string('access_url')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('accessed_at')->nullable();
            $table->integer('access_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('notification_token');
            $table->index('beneficiary_email');
            $table->index('estate_unique_id');
            $table->index('expires_at');
            $table->index(['is_active', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estate_notifications');
    }
};
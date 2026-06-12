<?php
// database/migrations/2026_05_11_000001_create_notification_requests_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('notification_requests')) {
            Schema::create('notification_requests', function (Blueprint $table) {
                $table->id();
                $table->string('session_id', 36);
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('deceased_name')->nullable();
                $table->string('deceased_nric', 20)->nullable();
                $table->date('death_date')->nullable();
                $table->string('death_place')->nullable();
                $table->string('recipient_email');
                $table->string('recipient_name')->nullable();
                $table->string('status')->default('pending_admin_approval');
                $table->json('request_metadata')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamp('rejected_at')->nullable();
                $table->text('rejection_reason')->nullable();
                $table->timestamp('sent_at')->nullable();
                $table->timestamps();
                
                $table->index('session_id');
                $table->index('user_id');
                $table->index('status');
                $table->index('recipient_email');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_requests');
    }
};
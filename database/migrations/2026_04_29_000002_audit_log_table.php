<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estate_pre_registration_id')->nullable()->constrained('estate_pre_registrations')->onDelete('set null');
            $table->string('session_id')->nullable();
            $table->string('action'); // estate_approved, notification_sent, document_viewed, etc.
            $table->foreignId('performed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('performed_by_name')->nullable();
            $table->string('performed_by_role')->nullable();
            $table->json('details')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            
            $table->index('estate_pre_registration_id');
            $table->index('action');
            $table->index('performed_by');
            $table->index('created_at');
            $table->index(['estate_pre_registration_id', 'action']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('audit_logs');
    }
};
<?php
// database/migrations/2026_06_01_000001_add_pdf_fields_to_notification_requests.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notification_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('notification_requests', 'pdf_path')) {
                $table->string('pdf_path')->nullable()->after('pdf_content');
            }
            if (!Schema::hasColumn('notification_requests', 'sent_by')) {
                $table->foreignId('sent_by')->nullable()->after('rejected_by')->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('notification_requests', 'resend_count')) {
                $table->integer('resend_count')->default(0)->after('last_resend_at');
            }
            if (!Schema::hasColumn('notification_requests', 'last_resend_at')) {
                $table->timestamp('last_resend_at')->nullable()->after('resend_count');
            }
        });
    }

    public function down(): void
    {
        Schema::table('notification_requests', function (Blueprint $table) {
            $table->dropColumn(['pdf_path', 'sent_by', 'resend_count', 'last_resend_at']);
        });
    }
};
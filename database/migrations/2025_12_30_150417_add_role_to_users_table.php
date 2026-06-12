<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Check if column exists before adding
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('user')->after('email');
            }
            
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status')->default('active')->after('role');
            }
            
            // Add Telegram fields after role
            if (!Schema::hasColumn('users', 'telegram_chat_id')) {
                $table->string('telegram_chat_id')->nullable()->after('role');
            }
            
            if (!Schema::hasColumn('users', 'telegram_username')) {
                $table->string('telegram_username')->nullable()->after('telegram_chat_id');
            }
            
            if (!Schema::hasColumn('users', 'telegram_session')) {
                $table->text('telegram_session')->nullable()->after('telegram_username');
            }
            
            if (!Schema::hasColumn('users', 'telegram_link_requested_at')) {
                $table->timestamp('telegram_link_requested_at')->nullable()->after('telegram_session');
            }
            
            if (!Schema::hasColumn('users', 'telegram_linked_at')) {
                $table->timestamp('telegram_linked_at')->nullable()->after('telegram_link_requested_at');
            }
            
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('updated_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop all added columns in reverse order
            $table->dropColumn([
                'role', 
                'status', 
                'telegram_chat_id',
                'telegram_username',
                'telegram_session',
                'telegram_link_requested_at',
                'telegram_linked_at',
                'last_login_at'
            ]);
        });
    }
};
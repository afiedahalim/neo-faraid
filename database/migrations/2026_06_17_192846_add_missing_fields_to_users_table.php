<?php

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
        Schema::table('users', function (Blueprint $table) {
            // Personal identification fields (editable by admin)
            if (!Schema::hasColumn('users', 'nric')) {
                $table->string('nric', 20)->nullable()->after('name');
            }

            if (!Schema::hasColumn('users', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('nric');
            }

            if (!Schema::hasColumn('users', 'gender')) {
                $table->enum('gender', ['male', 'female'])->nullable()->after('date_of_birth');
            }

            if (!Schema::hasColumn('users', 'contact_phone')) {
                $table->string('contact_phone', 20)->nullable()->after('gender');
            }

            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('contact_phone');
            }

            // Account settings (editable by admin)
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['user', 'admin'])->default('user')->after('address');
            }

            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['active', 'inactive'])->default('active')->after('role');
            }

            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('status');
            }

            // Telegram fields (optional)
            if (!Schema::hasColumn('users', 'telegram_chat_id')) {
                $table->string('telegram_chat_id')->nullable()->after('is_active');
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

            // Last login tracking
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('telegram_linked_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop only the columns we added (if they exist)
            $columns = [
                'nric',
                'date_of_birth',
                'gender',
                'contact_phone',
                'address',
                'role',
                'status',
                'is_active',
                'telegram_chat_id',
                'telegram_username',
                'telegram_session',
                'telegram_link_requested_at',
                'telegram_linked_at',
                'last_login_at'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
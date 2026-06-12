<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixUsersTableStatusColumn extends Migration
{
    public function up()
    {
        // Fix the status column to accept 'pending' value
        Schema::table('users', function (Blueprint $table) {
            // First, check if column exists
            if (Schema::hasColumn('users', 'status')) {
                // Modify column to accept longer string
                $table->string('status', 50)->default('pending')->change();
            } else {
                // Add column if it doesn't exist
                $table->string('status', 50)->default('pending')->after('email');
            }
            
            // Add missing columns
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(false)->after('status');
            }
            
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role', 50)->default('user')->after('email');
            }
        });
        
        // Update existing records
        DB::table('users')->update(['status' => 'active', 'is_active' => true]);
    }

    public function down()
    {
        // No down method needed for production
    }
}
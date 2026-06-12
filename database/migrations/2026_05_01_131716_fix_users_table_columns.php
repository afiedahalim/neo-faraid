<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixUsersTableColumns extends Migration
{
    public function up()
    {
        // Check and fix users table
        Schema::table('users', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('user')->after('email');
            }
            
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status')->default('pending')->after('role');
            } else {
                // Modify status column to accept longer values
                $table->string('status')->default('pending')->change();
            }
            
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(false)->after('status');
            }
            
            if (!Schema::hasColumn('users', 'nric')) {
                $table->string('nric')->nullable()->after('name');
            }
            
            if (!Schema::hasColumn('users', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('nric');
            }
            
            if (!Schema::hasColumn('users', 'gender')) {
                $table->enum('gender', ['male', 'female'])->nullable()->after('date_of_birth');
            }
            
            if (!Schema::hasColumn('users', 'contact_phone')) {
                $table->string('contact_phone')->nullable()->after('email');
            }
            
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('contact_phone');
            }
        });
        
        // Update existing users
        DB::table('users')->update([
            'status' => 'active',
            'is_active' => true,
        ]);
    }

    public function down()
    {
        // No down method needed
    }
}
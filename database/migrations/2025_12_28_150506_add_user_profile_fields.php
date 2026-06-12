<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add missing profile fields
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
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nric',
                'date_of_birth',
                'gender',
                'contact_phone',
                'address'
            ]);
        });
    }
};
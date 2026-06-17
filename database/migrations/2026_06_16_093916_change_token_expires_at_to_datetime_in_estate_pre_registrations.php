<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('estate_pre_registrations', function (Blueprint $table) {
            // Change timestamp columns to datetime
            $table->dateTime('token_expires_at')->nullable()->change();
            $table->dateTime('pdf_access_expires_at')->nullable()->change();
            $table->dateTime('video_access_expires_at')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('estate_pre_registrations', function (Blueprint $table) {
            $table->timestamp('token_expires_at')->nullable()->change();
            $table->timestamp('pdf_access_expires_at')->nullable()->change();
            $table->timestamp('video_access_expires_at')->nullable()->change();
        });
    }
};
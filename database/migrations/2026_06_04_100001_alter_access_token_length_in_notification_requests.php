<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('notification_requests', function (Blueprint $table) {
            // Change access_token column to VARCHAR(255) to accommodate long tokens
            $table->string('access_token', 255)->change();
        });
    }

    public function down()
    {
        Schema::table('notification_requests', function (Blueprint $table) {
            // Revert to original length (adjust as needed, e.g., 64)
            $table->string('access_token', 64)->change();
        });
    }
};
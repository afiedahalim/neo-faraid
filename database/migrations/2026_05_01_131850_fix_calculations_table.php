<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixCalculationsTable extends Migration
{
    public function up()
    {
        Schema::table('calculations', function (Blueprint $table) {
            if (!Schema::hasColumn('calculations', 'share_token')) {
                $table->string('share_token')->nullable()->unique()->after('id');
            }
            
            if (!Schema::hasColumn('calculations', 'share_expires_at')) {
                $table->timestamp('share_expires_at')->nullable()->after('share_token');
            }
        });
    }

    public function down()
    {
        Schema::table('calculations', function (Blueprint $table) {
            $table->dropColumn(['share_token', 'share_expires_at']);
        });
    }
}
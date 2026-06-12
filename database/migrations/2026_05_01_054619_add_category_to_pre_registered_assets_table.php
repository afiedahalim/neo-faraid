<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pre_registered_assets', function (Blueprint $table) {
            if (!Schema::hasColumn('pre_registered_assets', 'category')) {
                $table->string('category', 100)->nullable()->after('type');
            }
            if (!Schema::hasColumn('pre_registered_assets', 'ownership_percentage')) {
                $table->decimal('ownership_percentage', 5, 2)->default(100)->after('location');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pre_registered_assets', function (Blueprint $table) {
            if (Schema::hasColumn('pre_registered_assets', 'category')) {
                $table->dropColumn('category');
            }
            if (Schema::hasColumn('pre_registered_assets', 'ownership_percentage')) {
                $table->dropColumn('ownership_percentage');
            }
        });
    }
};
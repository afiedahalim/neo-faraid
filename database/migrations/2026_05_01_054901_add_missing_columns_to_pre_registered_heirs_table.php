<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pre_registered_heirs', function (Blueprint $table) {
            if (!Schema::hasColumn('pre_registered_heirs', 'relationship_type')) {
                $table->string('relationship_type')->nullable()->after('relationship');
            }
            if (!Schema::hasColumn('pre_registered_heirs', 'calculated_percentage')) {
                $table->decimal('calculated_percentage', 5, 2)->nullable()->after('share_percentage');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pre_registered_heirs', function (Blueprint $table) {
            if (Schema::hasColumn('pre_registered_heirs', 'relationship_type')) {
                $table->dropColumn('relationship_type');
            }
            if (Schema::hasColumn('pre_registered_heirs', 'calculated_percentage')) {
                $table->dropColumn('calculated_percentage');
            }
        });
    }
};
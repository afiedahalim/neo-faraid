<?php
// database/migrations/2026_06_04_000004_add_missing_columns_to_document_hashes.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('document_hashes', function (Blueprint $table) {
            if (!Schema::hasColumn('document_hashes', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('admin_action')
                    ->comment('Admin notes when document was approved/rejected');
            }
            if (!Schema::hasColumn('document_hashes', 'authenticity_score')) {
                $table->tinyInteger('authenticity_score')->unsigned()->nullable()->after('admin_notes')
                    ->comment('Authenticity score from Level 3 check');
            }
            if (!Schema::hasColumn('document_hashes', 'authenticity_details')) {
                $table->json('authenticity_details')->nullable()->after('authenticity_score')
                    ->comment('Detailed breakdown of authenticity score');
            }
        });
    }

    public function down()
    {
        Schema::table('document_hashes', function (Blueprint $table) {
            $columns = ['admin_notes', 'authenticity_score', 'authenticity_details'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('document_hashes', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
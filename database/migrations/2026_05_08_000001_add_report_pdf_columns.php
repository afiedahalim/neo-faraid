<?php
// database/migrations/2026_05_08_000001_add_report_pdf_columns.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('instant_estate_sessions', function (Blueprint $table) {
            $table->string('report_pdf_path')->nullable()->after('report_data');
        });
        
        Schema::table('notification_requests', function (Blueprint $table) {
            $table->longText('pdf_content')->nullable()->after('request_metadata');
        });
    }
    
    public function down()
    {
        Schema::table('instant_estate_sessions', function (Blueprint $table) {
            $table->dropColumn('report_pdf_path');
        });
        
        Schema::table('notification_requests', function (Blueprint $table) {
            $table->dropColumn('pdf_content');
        });
    }
};
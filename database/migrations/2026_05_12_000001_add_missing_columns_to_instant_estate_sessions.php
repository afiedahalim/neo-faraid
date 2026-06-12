<?php
// database/migrations/2026_05_12_000001_add_missing_columns_to_instant_estate_sessions.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('instant_estate_sessions', function (Blueprint $table) {
            // Add missing columns that are being referenced in the model
            if (!Schema::hasColumn('instant_estate_sessions', 'cause_of_death')) {
                $table->text('cause_of_death')->nullable()->after('death_place')
                    ->comment('Cause of death extracted from death certificate');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'father_name')) {
                $table->string('father_name')->nullable()->after('cause_of_death')
                    ->comment("Father's name extracted from death certificate");
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'mother_name')) {
                $table->string('mother_name')->nullable()->after('father_name')
                    ->comment("Mother's name extracted from death certificate");
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'spouse_name')) {
                $table->string('spouse_name')->nullable()->after('mother_name')
                    ->comment("Spouse's name extracted from death certificate");
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'processing_completed_at')) {
                $table->timestamp('processing_completed_at')->nullable()->after('processing_time_ms')
                    ->comment('Timestamp when OCR processing completed');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'metadata')) {
                $table->json('metadata')->nullable()->after('expires_at')
                    ->comment('Additional metadata for extensibility');
            }
        });
    }

    public function down(): void
    {
        Schema::table('instant_estate_sessions', function (Blueprint $table) {
            $columns = ['cause_of_death', 'father_name', 'mother_name', 'spouse_name', 'processing_completed_at', 'metadata'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('instant_estate_sessions', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
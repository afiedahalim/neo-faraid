<?php
// database/migrations/2026_05_12_000002_add_all_missing_columns_to_instant_estate_sessions.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('instant_estate_sessions', function (Blueprint $table) {
            // Personal Information Columns
            if (!Schema::hasColumn('instant_estate_sessions', 'gender')) {
                $table->enum('gender', ['male', 'female'])->nullable()->after('deceased_nric')
                    ->comment('Gender of the deceased');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('gender')
                    ->comment('Date of birth of the deceased');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'contact_email')) {
                $table->string('contact_email')->nullable()->after('death_place')
                    ->comment('Contact email address');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'contact_phone')) {
                $table->string('contact_phone', 20)->nullable()->after('contact_email')
                    ->comment('Contact phone number');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'residential_address')) {
                $table->text('residential_address')->nullable()->after('contact_phone')
                    ->comment('Residential address of the deceased');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'marital_status')) {
                $table->string('marital_status', 50)->nullable()->after('residential_address')
                    ->comment('Marital status of the deceased');
            }
            
            // Family Information Columns
            if (!Schema::hasColumn('instant_estate_sessions', 'father_name')) {
                $table->string('father_name')->nullable()->after('marital_status')
                    ->comment("Father's name");
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'mother_name')) {
                $table->string('mother_name')->nullable()->after('father_name')
                    ->comment("Mother's name");
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'spouse_name')) {
                $table->string('spouse_name')->nullable()->after('mother_name')
                    ->comment("Spouse's name");
            }
            
            // Death Information Columns
            if (!Schema::hasColumn('instant_estate_sessions', 'cause_of_death')) {
                $table->text('cause_of_death')->nullable()->after('death_place')
                    ->comment('Cause of death');
            }
            
            // Processing Tracking Columns
            if (!Schema::hasColumn('instant_estate_sessions', 'processing_completed_at')) {
                $table->timestamp('processing_completed_at')->nullable()->after('processing_time_ms')
                    ->comment('Timestamp when OCR processing completed');
            }
            
            // Metadata Column
            if (!Schema::hasColumn('instant_estate_sessions', 'metadata')) {
                $table->json('metadata')->nullable()->after('expires_at')
                    ->comment('Additional metadata for extensibility');
            }
        });
        
        // Update any existing records to have proper values
        DB::table('instant_estate_sessions')
            ->whereNull('marital_status')
            ->update(['marital_status' => 'single']);
    }

    public function down(): void
    {
        Schema::table('instant_estate_sessions', function (Blueprint $table) {
            $columns = [
                'gender',
                'date_of_birth',
                'contact_email',
                'contact_phone',
                'residential_address',
                'marital_status',
                'father_name',
                'mother_name',
                'spouse_name',
                'cause_of_death',
                'processing_completed_at',
                'metadata',
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('instant_estate_sessions', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
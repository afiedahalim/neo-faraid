<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('instant_estate_sessions', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('instant_estate_sessions', 'guest_email')) {
                $table->string('guest_email')->nullable()->after('user_id');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'guest_name')) {
                $table->string('guest_name')->nullable()->after('guest_email');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'file_path')) {
                $table->string('file_path')->nullable()->after('guest_name');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'original_filename')) {
                $table->string('original_filename')->nullable()->after('file_path');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'file_size')) {
                $table->integer('file_size')->nullable()->after('original_filename');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'file_mime')) {
                $table->string('file_mime')->nullable()->after('file_size');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'quality_check_passed')) {
                $table->boolean('quality_check_passed')->default(false)->after('status');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'quality_check_details')) {
                $table->json('quality_check_details')->nullable()->after('quality_check_passed');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'quality_issues')) {
                $table->json('quality_issues')->nullable()->after('quality_check_details');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'extracted_data')) {
                $table->json('extracted_data')->nullable()->after('quality_issues');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'missing_fields')) {
                $table->json('missing_fields')->nullable()->after('extracted_data');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'ocr_confidence')) {
                $table->integer('ocr_confidence')->nullable()->after('missing_fields');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'matched_record_type')) {
                $table->string('matched_record_type')->nullable()->after('ocr_confidence');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'matched_record_id')) {
                $table->unsignedBigInteger('matched_record_id')->nullable()->after('matched_record_type');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'matched_record_data')) {
                $table->json('matched_record_data')->nullable()->after('matched_record_id');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'report_data')) {
                $table->json('report_data')->nullable()->after('matched_record_data');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'report_pdf_path')) {
                $table->string('report_pdf_path')->nullable()->after('report_data');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'has_report')) {
                $table->boolean('has_report')->default(false)->after('report_pdf_path');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'has_pdf_report')) {
                $table->boolean('has_pdf_report')->default(false)->after('has_report');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'captcha_verified')) {
                $table->boolean('captcha_verified')->default(false)->after('has_pdf_report');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'notification_requested')) {
                $table->boolean('notification_requested')->default(false)->after('captcha_verified');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'notification_email')) {
                $table->string('notification_email')->nullable()->after('notification_requested');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'notification_request_id')) {
                $table->unsignedBigInteger('notification_request_id')->nullable()->after('notification_email');
            }
            
            // Deceased information columns
            if (!Schema::hasColumn('instant_estate_sessions', 'deceased_name')) {
                $table->string('deceased_name')->nullable()->after('notification_request_id');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'deceased_nric')) {
                $table->string('deceased_nric')->nullable()->after('deceased_name');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'death_date')) {
                $table->date('death_date')->nullable()->after('deceased_nric');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'death_place')) {
                $table->string('death_place')->nullable()->after('death_date');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('death_place');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'gender')) {
                $table->string('gender')->nullable()->after('date_of_birth');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'marital_status')) {
                $table->string('marital_status')->nullable()->after('gender');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'cause_of_death')) {
                $table->string('cause_of_death')->nullable()->after('marital_status');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'contact_email')) {
                $table->string('contact_email')->nullable()->after('cause_of_death');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'contact_phone')) {
                $table->string('contact_phone')->nullable()->after('contact_email');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'residential_address')) {
                $table->text('residential_address')->nullable()->after('contact_phone');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'father_name')) {
                $table->string('father_name')->nullable()->after('residential_address');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'mother_name')) {
                $table->string('mother_name')->nullable()->after('father_name');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'spouse_name')) {
                $table->string('spouse_name')->nullable()->after('mother_name');
            }
            
            // Processing columns
            if (!Schema::hasColumn('instant_estate_sessions', 'processing_attempts')) {
                $table->integer('processing_attempts')->default(0)->after('spouse_name');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'last_processing_attempt_at')) {
                $table->timestamp('last_processing_attempt_at')->nullable()->after('processing_attempts');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'processing_time_ms')) {
                $table->integer('processing_time_ms')->nullable()->after('last_processing_attempt_at');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'error_message')) {
                $table->text('error_message')->nullable()->after('processing_time_ms');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'metadata')) {
                $table->json('metadata')->nullable()->after('error_message');
            }
            
            // Timestamp columns
            if (!Schema::hasColumn('instant_estate_sessions', 'report_generated_at')) {
                $table->timestamp('report_generated_at')->nullable()->after('metadata');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'captcha_verified_at')) {
                $table->timestamp('captcha_verified_at')->nullable()->after('report_generated_at');
            }
            
            if (!Schema::hasColumn('instant_estate_sessions', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('captcha_verified_at');
            }
            
            // Make user_id nullable for guests
            if (Schema::hasColumn('instant_estate_sessions', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->change();
            }
        });
    }

    public function down()
    {
        // This migration is additive, no need to remove columns
    }
};
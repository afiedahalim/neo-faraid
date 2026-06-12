<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // =========================================================================
        // 1. Add debt settlement tracking to pre_registered_debts
        // =========================================================================
        Schema::table('pre_registered_debts', function (Blueprint $table) {
            if (!Schema::hasColumn('pre_registered_debts', 'amount_paid')) {
                $table->decimal('amount_paid', 15, 2)->default(0)->after('amount');
            }
            if (!Schema::hasColumn('pre_registered_debts', 'status')) {
                $table->string('status')->default('pending')->after('amount_paid');
            }
            if (!Schema::hasColumn('pre_registered_debts', 'is_settled')) {
                $table->boolean('is_settled')->default(false)->after('status');
            }
            if (!Schema::hasColumn('pre_registered_debts', 'settled_at')) {
                $table->timestamp('settled_at')->nullable()->after('is_settled');
            }
            if (!Schema::hasColumn('pre_registered_debts', 'settlement_reference')) {
                $table->string('settlement_reference')->nullable()->after('settled_at');
            }
            if (!Schema::hasColumn('pre_registered_debts', 'settlement_notes')) {
                $table->text('settlement_notes')->nullable()->after('settlement_reference');
            }
            if (!Schema::hasColumn('pre_registered_debts', 'payment_history')) {
                $table->json('payment_history')->nullable()->after('settlement_notes');
            }
            if (!Schema::hasColumn('pre_registered_debts', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_history');
            }
            if (!Schema::hasColumn('pre_registered_debts', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('paid_at');
            }
            if (!Schema::hasColumn('pre_registered_debts', 'reference_number')) {
                $table->string('reference_number')->nullable()->after('payment_method');
            }
        });

        // =========================================================================
        // 2. Add image quality validation fields to instant_estate_sessions
        // =========================================================================
        Schema::table('instant_estate_sessions', function (Blueprint $table) {
            if (!Schema::hasColumn('instant_estate_sessions', 'image_quality_score')) {
                $table->integer('image_quality_score')->nullable();
            }
            if (!Schema::hasColumn('instant_estate_sessions', 'is_blurry')) {
                $table->boolean('is_blurry')->default(false);
            }
            if (!Schema::hasColumn('instant_estate_sessions', 'has_glare')) {
                $table->boolean('has_glare')->default(false);
            }
            if (!Schema::hasColumn('instant_estate_sessions', 'is_cropped')) {
                $table->boolean('is_cropped')->default(false);
            }
            if (!Schema::hasColumn('instant_estate_sessions', 'quality_issues')) {
                $table->json('quality_issues')->nullable();
            }
            if (!Schema::hasColumn('instant_estate_sessions', 'watermark_applied')) {
                $table->string('watermark_applied')->nullable();
            }
            if (!Schema::hasColumn('instant_estate_sessions', 'watermarked_at')) {
                $table->timestamp('watermarked_at')->nullable();
            }
        });

        // =========================================================================
        // 3. Add manual OCR edit tracking to instant_estate_sessions
        // =========================================================================
        Schema::table('instant_estate_sessions', function (Blueprint $table) {
            if (!Schema::hasColumn('instant_estate_sessions', 'ocr_edited_manually')) {
                $table->boolean('ocr_edited_manually')->default(false);
            }
            if (!Schema::hasColumn('instant_estate_sessions', 'ocr_edited_at')) {
                $table->timestamp('ocr_edited_at')->nullable();
            }
            if (!Schema::hasColumn('instant_estate_sessions', 'original_ocr_data')) {
                $table->json('original_ocr_data')->nullable();
            }
            if (!Schema::hasColumn('instant_estate_sessions', 'manual_corrections')) {
                $table->json('manual_corrections')->nullable();
            }
            if (!Schema::hasColumn('instant_estate_sessions', 'ocr_reviewed')) {
                $table->boolean('ocr_reviewed')->default(false);
            }
            if (!Schema::hasColumn('instant_estate_sessions', 'ocr_reviewed_at')) {
                $table->timestamp('ocr_reviewed_at')->nullable();
            }
        });

        // =========================================================================
        // 4. Add CAPTCHA verification tracking to instant_estate_sessions
        //    FIXED: Removed 'after' clause that was causing the error
        // =========================================================================
        Schema::table('instant_estate_sessions', function (Blueprint $table) {
            if (!Schema::hasColumn('instant_estate_sessions', 'captcha_verified')) {
                $table->boolean('captcha_verified')->default(false);
            }
            if (!Schema::hasColumn('instant_estate_sessions', 'captcha_verified_at')) {
                $table->timestamp('captcha_verified_at')->nullable();
            }
            if (!Schema::hasColumn('instant_estate_sessions', 'captcha_verified_by')) {
                $table->unsignedBigInteger('captcha_verified_by')->nullable();
            }
        });

        // =========================================================================
        // 5. Add notification request logging to notification_requests
        // =========================================================================
        Schema::table('notification_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('notification_requests', 'request_logged_at')) {
                $table->timestamp('request_logged_at')->nullable();
            }
            if (!Schema::hasColumn('notification_requests', 'request_ip')) {
                $table->string('request_ip', 45)->nullable();
            }
            if (!Schema::hasColumn('notification_requests', 'request_user_agent')) {
                $table->text('request_user_agent')->nullable();
            }
            if (!Schema::hasColumn('notification_requests', 'death_certificate_validated')) {
                $table->boolean('death_certificate_validated')->default(false);
            }
            if (!Schema::hasColumn('notification_requests', 'death_certificate_validated_at')) {
                $table->timestamp('death_certificate_validated_at')->nullable();
            }
        });

        // =========================================================================
        // 6. Add secure access link fields to estate_pre_registrations
        // =========================================================================
        Schema::table('estate_pre_registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('estate_pre_registrations', 'secure_access_links_generated')) {
                $table->boolean('secure_access_links_generated')->default(false);
            }
            if (!Schema::hasColumn('estate_pre_registrations', 'secure_access_links_generated_at')) {
                $table->timestamp('secure_access_links_generated_at')->nullable();
            }
            if (!Schema::hasColumn('estate_pre_registrations', 'secure_access_links')) {
                $table->json('secure_access_links')->nullable();
            }
        });

        // =========================================================================
        // 7. Create secure_access_logs table
        // =========================================================================
        if (!Schema::hasTable('secure_access_logs')) {
            Schema::create('secure_access_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('estate_pre_registration_id')->constrained()->onDelete('cascade');
                $table->string('access_token', 64);
                $table->string('recipient_type');
                $table->string('recipient_email');
                $table->string('recipient_name');
                $table->timestamp('accessed_at')->nullable();
                $table->string('accessed_ip', 45)->nullable();
                $table->text('accessed_user_agent')->nullable();
                $table->integer('access_count')->default(0);
                $table->timestamp('expires_at');
                $table->timestamps();

                $table->index('access_token');
                $table->index('estate_pre_registration_id');
                $table->index('expires_at');
            });
        }
    }

    public function down()
    {
        Schema::table('pre_registered_debts', function (Blueprint $table) {
            $columns = [
                'amount_paid', 'status', 'is_settled', 'settled_at',
                'settlement_reference', 'settlement_notes', 'payment_history',
                'paid_at', 'payment_method', 'reference_number'
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('pre_registered_debts', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('instant_estate_sessions', function (Blueprint $table) {
            $columns = [
                'image_quality_score', 'is_blurry', 'has_glare', 'is_cropped',
                'quality_issues', 'watermark_applied', 'watermarked_at',
                'ocr_edited_manually', 'ocr_edited_at', 'original_ocr_data',
                'manual_corrections', 'ocr_reviewed', 'ocr_reviewed_at',
                'captcha_verified', 'captcha_verified_at', 'captcha_verified_by'
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('instant_estate_sessions', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('notification_requests', function (Blueprint $table) {
            $columns = [
                'request_logged_at', 'request_ip', 'request_user_agent',
                'death_certificate_validated', 'death_certificate_validated_at'
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('notification_requests', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('estate_pre_registrations', function (Blueprint $table) {
            $columns = [
                'secure_access_links_generated', 'secure_access_links_generated_at', 'secure_access_links'
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('estate_pre_registrations', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::dropIfExists('secure_access_logs');
    }
};
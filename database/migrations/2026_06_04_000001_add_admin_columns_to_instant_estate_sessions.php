<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('instant_estate_sessions', function (Blueprint $table) {
            if (!Schema::hasColumn('instant_estate_sessions', 'authenticity_score')) {
                $table->tinyInteger('authenticity_score')->unsigned()->nullable()->after('ocr_confidence')
                    ->comment('0-100 score from Level 3 document authenticity check');
            }
            if (!Schema::hasColumn('instant_estate_sessions', 'admin_status')) {
                $table->enum('admin_status', ['pending_review', 'pending_approval', 'approved', 'rejected'])
                    ->default('pending_review')->after('status')
                    ->comment('Admin review state after Level 3 scoring');
            }
            if (!Schema::hasColumn('instant_estate_sessions', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('admin_status');
            }
            if (!Schema::hasColumn('instant_estate_sessions', 'reviewed_by_admin_id')) {
                $table->unsignedBigInteger('reviewed_by_admin_id')->nullable()->after('rejection_reason');
                $table->foreign('reviewed_by_admin_id')->references('id')->on('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('instant_estate_sessions', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable()->after('reviewed_by_admin_id');
            }
            if (!Schema::hasColumn('instant_estate_sessions', 'file_hash')) {
                $table->char('file_hash', 64)->nullable()->after('file_path')
                    ->comment('SHA-256 hash of uploaded file for duplicate detection');
            }
            if (!Schema::hasColumn('instant_estate_sessions', 'registration_number')) {
                $table->string('registration_number', 50)->nullable()->after('death_place')
                    ->comment('No. Daftar / Registration Number from certificate');
            }
        });
    }

    public function down()
    {
        Schema::table('instant_estate_sessions', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by_admin_id']);
            $table->dropColumn([
                'authenticity_score', 'admin_status', 'rejection_reason',
                'reviewed_by_admin_id', 'reviewed_at', 'file_hash', 'registration_number'
            ]);
        });
    }
};
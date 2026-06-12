<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pre_registered_wasiyyah', function (Blueprint $table) {
            if (!Schema::hasColumn('pre_registered_wasiyyah', 'allocation_type')) {
                $table->string('allocation_type', 50)->nullable()->default('percentage');
            }
            if (!Schema::hasColumn('pre_registered_wasiyyah', 'priority_level')) {
                $table->string('priority_level', 20)->nullable()->default('medium');
            }
            if (!Schema::hasColumn('pre_registered_wasiyyah', 'verification_status')) {
                $table->string('verification_status', 20)->nullable()->default('pending');
            }
            if (!Schema::hasColumn('pre_registered_wasiyyah', 'execution_grace_period_days')) {
                $table->integer('execution_grace_period_days')->nullable()->default(90);
            }
            if (!Schema::hasColumn('pre_registered_wasiyyah', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('pre_registered_wasiyyah', 'is_executed')) {
                $table->boolean('is_executed')->default(false);
            }
            if (!Schema::hasColumn('pre_registered_wasiyyah', 'is_revoked')) {
                $table->boolean('is_revoked')->default(false);
            }
            if (!Schema::hasColumn('pre_registered_wasiyyah', 'is_disputed')) {
                $table->boolean('is_disputed')->default(false);
            }
            if (!Schema::hasColumn('pre_registered_wasiyyah', 'is_charity')) {
                $table->boolean('is_charity')->default(false);
            }
            if (!Schema::hasColumn('pre_registered_wasiyyah', 'is_non_muslim')) {
                $table->boolean('is_non_muslim')->default(false);
            }
            if (!Schema::hasColumn('pre_registered_wasiyyah', 'execution_priority')) {
                $table->integer('execution_priority')->default(0);
            }
            if (!Schema::hasColumn('pre_registered_wasiyyah', 'tags')) {
                $table->json('tags')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('pre_registered_wasiyyah', function (Blueprint $table) {
            $columns = [
                'allocation_type', 'priority_level', 'verification_status',
                'execution_grace_period_days', 'description', 'is_executed',
                'is_revoked', 'is_disputed', 'is_charity', 'is_non_muslim',
                'execution_priority', 'tags'
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('pre_registered_wasiyyah', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
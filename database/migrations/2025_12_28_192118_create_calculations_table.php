<?php
// database/migrations/2025_12_28_192118_create_calculations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('calculations')) {
            Schema::create('calculations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                
                // Deceased Information
                $table->string('deceased_name');
                $table->string('deceased_nric', 20)->nullable();
                $table->enum('deceased_gender', ['male', 'female']);
                $table->date('date_of_death');
                $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed']);
                
                // Death details
                $table->string('cause_of_death', 255)->nullable();
                $table->string('death_place', 255)->nullable();
                $table->string('contact_email', 255)->nullable();
                $table->string('contact_phone', 20)->nullable();
                $table->text('residential_address')->nullable();
                
                // Spouse information
                $table->integer('wife_count')->default(0);
                $table->integer('husband_count')->default(0);
                
                // Parent information
                $table->enum('father_status', ['alive', 'deceased', 'none'])->default('none');
                $table->enum('mother_status', ['alive', 'deceased', 'none'])->default('none');
                
                // Grandparents
                $table->enum('fathers_father_status', ['alive', 'deceased'])->default('deceased');
                $table->enum('fathers_mother_status', ['alive', 'deceased'])->default('deceased');
                $table->enum('mothers_mother_status', ['alive', 'deceased'])->default('deceased');
                
                // Children information
                $table->integer('son_count')->default(0);
                $table->integer('daughter_count')->default(0);
                
                // Siblings information
                $table->integer('full_brother_count')->default(0);
                $table->integer('full_sister_count')->default(0);
                $table->integer('paternal_brother_count')->default(0);
                $table->integer('paternal_sister_count')->default(0);
                $table->integer('maternal_brother_count')->default(0);
                $table->integer('maternal_sister_count')->default(0);
                
                // Financial information
                $table->decimal('total_assets', 15, 2)->default(0);
                $table->decimal('net_assets', 15, 2)->default(0);
                
                // Counts
                $table->integer('total_heirs')->default(0);
                $table->integer('eligible_heirs_count')->default(0);
                
                // Scenario information
                $table->integer('scenario_number')->nullable();
                $table->unsignedBigInteger('faraid_scenario_id')->nullable();
                $table->string('scenario_description')->nullable();
                $table->string('calculation_method')->default('local');
                
                // JSON data
                $table->json('heirs_data')->nullable();
                $table->json('assets_data')->nullable();
                $table->json('calculation_data')->nullable();
                $table->json('distribution_summary')->nullable();
                $table->json('chart_data')->nullable();
                $table->json('tree_data')->nullable();
                $table->json('scenario_data')->nullable();
                $table->json('scenario_rules_applied')->nullable();
                
                // Tree generation fields
                $table->string('tree_generation_status')->default('pending');
                $table->timestamp('tree_generation_attempted_at')->nullable();
                $table->string('family_tree_image')->nullable();
                $table->timestamp('tree_generated_at')->nullable();
                $table->text('tree_generation_error')->nullable();
                
                // Share functionality
                $table->string('share_token')->nullable()->unique();
                $table->timestamp('share_expires_at')->nullable();
                $table->text('graphviz_data')->nullable();
                
                // Metadata
                $table->string('calculation_hash')->unique();
                $table->timestamps();
                
                // Indexes
                $table->index('user_id');
                $table->index('deceased_nric');
                $table->index('calculation_hash');
                $table->index('created_at');
                $table->index(['user_id', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('calculations');
    }
};
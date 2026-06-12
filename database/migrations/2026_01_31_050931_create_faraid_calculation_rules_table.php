<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faraid_calculation_rules', function (Blueprint $table) {
            $table->id();
            $table->integer('scenario_number')->unique();
            $table->string('scenario_name', 100);
            $table->text('scenario_description');
            $table->integer('priority')->default(1);
            $table->json('conditions');
            $table->json('distribution_rules');
            $table->text('calculation_logic')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('scenario_number');
            $table->index('priority');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faraid_calculation_rules');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question', 500);
            $table->text('answer');
            $table->enum('category', ['gettingStarted', 'calculations', 'securityPrivacy', 'others'])
                  ->default('others');
            $table->integer('order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->string('meta_description', 255)->nullable();
            $table->string('read_time', 50)->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['is_published', 'order']);
            $table->index('category');
            $table->index('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
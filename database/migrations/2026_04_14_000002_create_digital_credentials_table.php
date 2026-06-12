<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('digital_credentials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estate_pre_registration_id')->constrained('estate_pre_registrations')->onDelete('cascade');
            $table->string('platform');
            $table->string('username');
            $table->text('encrypted_password');
            $table->text('security_questions')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('estate_pre_registration_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('digital_credentials');
    }
};
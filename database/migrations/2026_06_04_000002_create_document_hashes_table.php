<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('document_hashes', function (Blueprint $table) {
            $table->id();
            $table->char('file_hash', 64)->unique();
            $table->string('deceased_nric', 20)->nullable();
            $table->date('death_date')->nullable();
            $table->string('registration_number', 50)->nullable();
            $table->string('first_session_id', 255);
            $table->string('last_session_id', 255);
            $table->integer('upload_count')->default(1);
            $table->enum('admin_action', ['approved', 'rejected'])->nullable();
            $table->timestamps();

            $table->index('file_hash');
            $table->index('deceased_nric');
        });
    }

    public function down()
    {
        Schema::dropIfExists('document_hashes');
    }
};
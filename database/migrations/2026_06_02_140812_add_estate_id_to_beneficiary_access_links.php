<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('beneficiary_access_links', function (Blueprint $table) {
            $table->unsignedBigInteger('estate_id')->after('id'); // or after any column you prefer
            // If it should be a foreign key:
            // $table->foreign('estate_id')->references('id')->on('estates')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('beneficiary_access_links', function (Blueprint $table) {
            $table->dropColumn('estate_id');
        });
    }
};
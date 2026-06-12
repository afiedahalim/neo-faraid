<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Set session_id to NULL for any row where session_id is not a valid UUID
        DB::statement("
            UPDATE instant_estate_sessions 
            SET session_id = NULL 
            WHERE session_id IS NULL 
               OR session_id = '' 
               OR LOWER(session_id) = 'null'
               OR LENGTH(session_id) != 36
               OR session_id NOT REGEXP '^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$'
        ");
    }

    public function down()
    {
        // Cannot revert data changes
    }
};
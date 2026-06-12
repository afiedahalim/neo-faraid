<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Set session_id to NULL for any row where session_id is not a valid UUID
        // This includes 'null', 'NULL', empty strings, or any other invalid format.
        DB::table('instant_estate_sessions')
            ->whereNotNull('session_id')
            ->whereRaw('LOWER(session_id) NOT REGEXP "^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$"')
            ->update(['session_id' => null]);
    }

    public function down()
    {
        // Cannot revert
    }
};
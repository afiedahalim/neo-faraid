<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Set session_id to NULL where it is empty, 'null', or not a valid UUID
        DB::table('instant_estate_sessions')
            ->whereNull('session_id')
            ->orWhere('session_id', '')
            ->orWhere('session_id', 'null')
            ->orWhereRaw('LENGTH(session_id) < 30')
            ->update(['session_id' => null]);

        // Also fix records where session_id is not a UUID format
        DB::table('instant_estate_sessions')
            ->whereNotNull('session_id')
            ->whereRaw('session_id NOT REGEXP "^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$"')
            ->update(['session_id' => null]);
    }

    public function down()
    {
        // Cannot revert data changes
    }
};
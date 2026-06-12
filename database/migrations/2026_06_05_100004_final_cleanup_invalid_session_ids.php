<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Set session_id to NULL for any row where session_id is not a valid UUID
        DB::table('instant_estate_sessions')
            ->whereNull('session_id')
            ->orWhere('session_id', '')
            ->orWhereRaw('LOWER(session_id) = ?', ['null'])
            ->orWhereRaw('LENGTH(session_id) != 36')
            ->orWhereRaw('session_id NOT REGEXP "^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$"')
            ->update(['session_id' => null]);
    }

    public function down()
    {
        // Cannot revert data changes
    }
};
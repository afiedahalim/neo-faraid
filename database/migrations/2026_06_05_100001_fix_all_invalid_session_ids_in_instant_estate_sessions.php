<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Set session_id to NULL where it is any kind of invalid value
        DB::table('instant_estate_sessions')
            ->whereNull('session_id')
            ->orWhere('session_id', '')
            ->orWhere('session_id', 'null')
            ->orWhere('session_id', 'NULL')
            ->orWhere('session_id', '(null)')
            ->orWhereRaw('LENGTH(session_id) < 30')
            ->orWhereRaw('session_id NOT REGEXP "^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$"')
            ->update(['session_id' => null]);
    }

    public function down()
    {
        // Cannot revert data changes
    }
};
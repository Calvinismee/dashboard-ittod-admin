<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Normalize values created by the old numeric participation type form.
     */
    public function up(): void
    {
        DB::table('event')
            ->whereNotIn('participation_type', ['individual', 'team'])
            ->update(['participation_type' => 'team']);
    }

    /**
     * This data correction cannot be safely reversed.
     */
    public function down(): void
    {
        //
    }
};

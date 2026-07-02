<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Rejected transactions must have a rejection reason. Rows without one
     * came from document-verification errors that were previously mixed with
     * payment verification.
     */
    public function up(): void
    {
        DB::table('team')
            ->where('is_document_verified', 'approved')
            ->where('is_verified', 'rejected')
            ->whereNull('verification_error')
            ->update(['is_verified' => 'pending']);
    }

    /**
     * The corrected status cannot be safely inferred in reverse.
     */
    public function down(): void
    {
        //
    }
};

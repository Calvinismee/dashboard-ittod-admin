<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Restore the member verification flag when migration history and the
     * physical database schema are out of sync.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('team_member', 'is_verified')) {
            Schema::table('team_member', function (Blueprint $table) {
                $table->boolean('is_verified')->default(false)->after('role');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('team_member', 'is_verified')) {
            Schema::table('team_member', function (Blueprint $table) {
                $table->dropColumn('is_verified');
            });
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('team_member', function (Blueprint $table) {
            $table->string('user_id', 191);
            $table->string('team_id', 191);
            $table->enum('role', ['leader', 'member']);
            $table->text('verification_error')->nullable();
            $table->string('kartu_id', 191)->nullable();

            $table->primary(['user_id', 'team_id']);
            $table->foreign('user_id')->references('id')->on('user')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('team_id')->references('id')->on('team')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('kartu_id')->references('id')->on('media')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_member');
    }
};

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
        Schema::create('team', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->string('competition_id', 191);
            $table->text('team_name');
            $table->string('team_code', 191)->unique();
            $table->integer('max_member')->default(3);
            $table->tinyInteger('is_verified')->default(0);
            $table->text('verification_error')->nullable();
            $table->dateTime('created_at', 3)->useCurrent();
            $table->dateTime('updated_at', 3)->nullable();
            $table->string('payment_proof_id', 191)->nullable();

            $table->foreign('competition_id')->references('id')->on('event')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('payment_proof_id')->references('id')->on('media')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team');
    }
};

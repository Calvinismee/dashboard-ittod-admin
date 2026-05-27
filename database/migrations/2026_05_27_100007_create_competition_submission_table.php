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
        Schema::create('competition_submission', function (Blueprint $table) {
            $table->string('team_id', 191);
            $table->string('competition_id', 191);
            $table->dateTime('created_at', 3)->useCurrent();
            $table->dateTime('updated_at', 3)->nullable();
            $table->json('submission_object')->nullable();

            $table->primary(['team_id', 'competition_id']);
            $table->foreign('team_id')->references('id')->on('team')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('competition_id')->references('id')->on('event')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competition_submission');
    }
};

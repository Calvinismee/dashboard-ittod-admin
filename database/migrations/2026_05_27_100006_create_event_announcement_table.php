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
        Schema::create('event_announcement', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->string('event_id', 191);
            $table->string('author_id', 191);
            $table->text('title');
            $table->text('description');
            $table->dateTime('created_at', 3)->useCurrent();
            $table->dateTime('updated_at', 3);

            $table->foreign('event_id')->references('id')->on('event')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('author_id')->references('id')->on('user')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_announcement');
    }
};

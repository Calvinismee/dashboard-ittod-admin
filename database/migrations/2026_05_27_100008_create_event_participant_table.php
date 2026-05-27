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
        Schema::create('event_participant', function (Blueprint $table) {
            $table->string('user_id', 191);
            $table->string('event_id', 191);
            $table->dateTime('date_added', 3)->useCurrent();
            $table->string('payment_proof', 191)->nullable();
            $table->enum('payment_verification', ['pending', 'accepted', 'rejected'])->default('pending');

            $table->primary(['user_id', 'event_id']);
            $table->foreign('user_id')->references('id')->on('user')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('event_id')->references('id')->on('event')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_participant');
    }
};

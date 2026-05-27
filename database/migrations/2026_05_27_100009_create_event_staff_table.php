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
        Schema::create('event_staff', function (Blueprint $table) {
            $table->string('event_id', 191);
            $table->string('user_id', 191);
            $table->timestamps();

            $table->primary(['event_id', 'user_id']);
            $table->foreign('event_id')->references('id')->on('event')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('user_identity')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_staff');
    }
};

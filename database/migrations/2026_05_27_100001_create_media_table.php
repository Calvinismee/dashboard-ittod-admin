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
        Schema::create('media', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->string('uploader_id', 191);
            $table->string('name', 191)->unique();
            $table->enum('grouping', ['payments', 'dokum_tahun_lalu', 'competition_submission', 'twibbon'])->nullable();
            $table->enum('type', ['image', 'pdf']);
            $table->string('url', 191);
            $table->dateTime('created_at', 3)->useCurrent();
            $table->dateTime('updated_at', 3)->useCurrent();

            $table->foreign('uploader_id')->references('id')->on('user')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};

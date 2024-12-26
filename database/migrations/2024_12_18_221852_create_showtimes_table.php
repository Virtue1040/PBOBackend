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
        Schema::create('showtimes', function (Blueprint $table) {
            $table->integer('id', 1)->primary()->length(11);
            $table->integer('id_movie')->length(11);
            $table->foreign('id_movie')->references('id')->on('movies')->onDelete('cascade');
            $table->integer('id_studio')->lenght(11);
            $table->foreign('id_studio')->references('id')->on('studios')->onDelete('cascade');
            $table->timestamp('time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('showtimes');
    }
};

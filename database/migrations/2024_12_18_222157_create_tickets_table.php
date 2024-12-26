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
        Schema::create('tickets', function (Blueprint $table) {
            $table->integer('id', 1)->primary()->length(11);
            $table->string("id_order");
            $table->foreign('id_order')->references('id_order')->on('orders')->onDelete('cascade');
            $table->integer('id_user')->lenght(11);
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->integer('id_showtime')->lenght(11);
            $table->foreign('id_showtime')->references('id')->on('showtimes')->onDelete('cascade');
            $table->char('seatNumber', 10);
            $table->double('price');
            $table->char('status', 10);
            $table->timestamps();

            $table->unique(['seatNumber', 'id_showtime']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};

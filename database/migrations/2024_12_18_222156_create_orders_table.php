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
        Schema::create('orders', function (Blueprint $table) {
            $table->string("id_order")->primary();
            $table->integer("id_user")->lenght(11);
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->string("order_type")->default("ticket");
            $table->double("order_nominal")->lenght(11);
            $table->char("status", 10)->default("pending");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

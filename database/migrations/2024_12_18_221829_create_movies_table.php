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
        Schema::create('movies', function (Blueprint $table) {
            $table->integer('id', 1)->primary()->length(11);
            $table->string('title');
            $table->string("sinopsis")->length(255);
            $table->string('genre');
            $table->time('duration');
            $table->double('price');    
            $table->string('cover')->nullable();
            $table->date('expire');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};

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
        Schema::create('fanfics', function (Blueprint $table) {
            $table->id();
            $table->string('author');
            $table->string('fandom')->nullable();
            $table->string('relationships')->nullable();
            $table->string('language')->nullable();
            $table->integer('words')->nullable();
            $table->integer('read_chapters')->nullable();
            $table->integer('total_chapters')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fanfics');
    }
};

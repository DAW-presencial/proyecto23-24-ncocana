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
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('author');
            $table->string('fandom')->nullable();
            $table->string('relationships')->nullable();
            // $table->string('tags')->nullable();
            $table->string('language')->nullable();
            $table->integer('words')->nullable();
            $table->integer('chapters_read')->nullable();
            $table->integer('total_chapters')->nullable();
            $table->mediumText('synopsis')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookmarks');
    }
};

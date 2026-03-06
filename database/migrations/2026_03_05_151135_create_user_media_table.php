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
        Schema::create('user_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('list_type', ['favorite', 'watchlist', 'recent']);
            $table->enum('media_type', ['movie', 'tv']);
            $table->integer('tmdb_id');
            $table->string('title')->nullable();
            $table->string('poster_path')->nullable();
            $table->float('vote_average')->nullable();
            $table->timestamps();
            $table->unique(['user_id','list_type','media_type','tmdb_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_media');
    }
};

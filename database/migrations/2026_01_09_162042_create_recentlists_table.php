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
        Schema::create('recentlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type');
            $table->unsignedBigInteger('tmdb_id');
            $table->string('title')->nullable();
            $table->string('poster_path')->nullable();
            $table->decimal('vote_average', 3, 1)->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'type', 'tmdb_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recentlists');
    }
};

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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_api_id')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('background_image')->nullable();
            $table->date('released')->nullable();
            $table->json('platforms')->nullable();
            $table->json('genres')->nullable();
            $table->json('publishers')->nullable();
            $table->decimal('rating', 3, 1)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};

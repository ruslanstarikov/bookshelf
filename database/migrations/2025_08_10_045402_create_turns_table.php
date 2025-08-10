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
        Schema::create('turns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player_id');
            $table->unsignedBigInteger('round_id');
            $table->string('isbn')->nullable();
            $table->string('title')->nullable();
            $table->string('author')->nullable();
            $table->integer('score');
            $table->string('canonical_title')->nullable();
            $table->timestamps();

            $table->index('player_id');
            $table->index('round_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turns');
    }
};

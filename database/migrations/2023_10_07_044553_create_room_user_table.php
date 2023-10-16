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
        Schema::create('room_user', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('room_id');
            $table->timestamps();
            $table->unique(['user_id', 'room_id']);

            $table->foreign('user_id')->references('uuid')->on('user')->onDelete('cascade');
            $table->foreign('room_id')->references('uuid')->on('rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_user');
    }
};

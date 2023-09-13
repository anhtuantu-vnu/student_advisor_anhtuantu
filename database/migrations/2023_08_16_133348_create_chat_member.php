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
        Schema::create('chat_member', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('user_id');
            $table->foreign('user_id')->references('uuid')->on('user');
            $table->string('channel_id');
            $table->foreign('channel_id')->references('uuid')->on('chat_channel');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_member');
    }
};

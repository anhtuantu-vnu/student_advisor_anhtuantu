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
        Schema::create('chat_message', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('uuid')->primary();
            $table->text('content');
            $table->string('channel_id');
            $table->foreign('channel_id')->references('uuid')->on('chat_channel');
            $table->string('sender_id');
            $table->string('receiver_id');
            $table->string('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_message');
    }
};

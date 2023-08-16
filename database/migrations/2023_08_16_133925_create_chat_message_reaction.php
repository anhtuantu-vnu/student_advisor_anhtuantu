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
        Schema::create('chat_message_reaction', function (Blueprint $table) {
            $table->integer('id');
            $table->string('uuid')->primary();
            $table->string('chat_message_id');
            $table->foreign('chat_message_id')->references('uuid')->on('chat_message');
            $table->string('type');
            $table->string('user_id');
            $table->foreign('user_id')->references('uuid')->on('user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_message_reaction');
    }
};

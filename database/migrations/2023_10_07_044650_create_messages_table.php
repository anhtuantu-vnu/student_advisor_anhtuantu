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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('room_id')->nullable();
            $table->text('content');
            $table->string('type');
            $table->timestamps();

            $table->foreign('user_id')->references('uuid')->on('user')->onDelete('cascade');
            $table->foreign('room_id')->references('uuid')->on('rooms')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};

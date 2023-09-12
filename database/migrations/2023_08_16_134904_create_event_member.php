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
        Schema::create('user', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string('uuid');
            $table->string('event_id');
            $table->foreign('event_id')->references('uuid')->on('event');
            $table->string('user_id');
            $table->foreign('user_id')->references('uuid')->on('user');
            $table->timestamps();

            // Đặt 'uuid' làm khoá chính
            $table->primary('uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_member');
    }
};

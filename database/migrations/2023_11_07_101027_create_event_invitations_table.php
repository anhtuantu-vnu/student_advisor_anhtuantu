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
        Schema::create('event_invitations', function (Blueprint $table) {
            $table->id();
            $table->string('origin_user');
            $table->string('target_user');
            $table->string('event_id');
            $table->enum('status', ['no_response', 'going', 'reject']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_invitations');
    }
};

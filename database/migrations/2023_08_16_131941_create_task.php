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
        Schema::create('task', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('name');
            $table->string('description');
            $table->string('status');
            $table->string('assigned_to');
            $table->dateTime('due_date');
            $table->string('created_by');
            $table->string('plan_id');
            $table->foreign('plan_id')->references('uuid')->on('plan');
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
        Schema::dropIfExists('task');
    }
};

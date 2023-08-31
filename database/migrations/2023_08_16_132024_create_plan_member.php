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
        Schema::create('plan_member', function (Blueprint $table) {
            $table->integer('id');
            $table->string('uuid')->primary();
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
        Schema::dropIfExists('plan_member');
    }
};

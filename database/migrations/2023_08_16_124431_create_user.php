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
            $table->integer('id');
            $table->string('uuid')->primary();
            $table->string('first_name');
            $table->string('last_name');
            $table->enum('role' , ['teacher' , 'student', 'admin']);
//            $table->string('role_id');
//            $table->foreign('role_id')->references('uuid')->on('role');
            $table->string('unique_id');
            $table->string('email');
            $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};

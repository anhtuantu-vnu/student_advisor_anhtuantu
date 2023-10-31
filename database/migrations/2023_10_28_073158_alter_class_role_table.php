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
        Schema::table('class_roles', function (Blueprint $table) {
            $table->string('class_id');
            $table->enum('role', ['teacher', 'student']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_roles', function (Blueprint $table) {
            $table->dropColumn('class_id');
            $table->dropColumn('role');
        });
    }
};

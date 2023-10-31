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
        Schema::table('intake', function (Blueprint $table) {
            $table->float('attendance_points')->nullable();
            $table->float('mid_term_points')->nullable();
            $table->float('last_term_points')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('intake', function (Blueprint $table) {
            $table->dropColumn('attendance_points');
            $table->dropColumn('mid_term_points');
            $table->dropColumn('last_term_points');
        });
    }
};

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
        Schema::dropIfExists('intake');

        Schema::table('intakes', function (Blueprint $table) {
            $table->integer('start_hour');
            $table->integer('start_minute');
            $table->integer('end_hour');
            $table->integer('end_minute');
            $table->string('week_days');
        });

        Schema::table('intake_members', function (Blueprint $table) {
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
        Schema::table('intakes', function (Blueprint $table) {
            $table->dropColumn('start_hour');
            $table->dropColumn('start_minute');
            $table->dropColumn('end_hour');
            $table->dropColumn('end_minute');
            $table->dropColumn('week_days');
        });

        Schema::table('intake_members', function (Blueprint $table) {
            $table->dropColumn('attendance_points');
            $table->dropColumn('mid_term_points');
            $table->dropColumn('last_term_points');
        });
    }
};

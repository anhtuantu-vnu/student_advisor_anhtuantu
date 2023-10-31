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
            $table->string('week_days');
            $table->integer('start_hour');
            $table->integer('start_minute');
            $table->integer('end_hour');
            $table->integer('end_minute');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('intake', function (Blueprint $table) {
            $table->dropColumn('week_days');
            $table->dropColumn('start_hour');
            $table->dropColumn('start_minute');
            $table->dropColumn('end_hour');
            $table->dropColumn('end_minute');
        });
    }
};

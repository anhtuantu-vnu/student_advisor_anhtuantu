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
        Schema::table('event', function (Blueprint $table) {
            $table->string('created_by');
            $table->string('tags')->nullable();
            $table->text('description');
            $table->integer('start_hour');
            $table->integer('start_minute');
            $table->integer('end_hour');
            $table->integer('end_minute');
            $table->text('files');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('tags');
            $table->dropColumn('description');
            $table->dropColumn('start_hour');
            $table->dropColumn('start_minute');
            $table->dropColumn('end_hour');
            $table->dropColumn('end_minute');
            $table->dropColumn('files');
        });
    }
};

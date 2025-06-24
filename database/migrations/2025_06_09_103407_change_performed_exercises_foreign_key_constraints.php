<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('performed_exercises', function (Blueprint $table) {
            $table->dropForeign(['performed_workout_id']);
        });

        Schema::table('performed_exercises', function (Blueprint $table) {
            $table->unsignedBigInteger('performed_workout_id')->nullable()->change();
        });

        Schema::table('performed_exercises', function (Blueprint $table) {
            $table->foreign('performed_workout_id')
                ->references('id')
                ->on('performed_workouts')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('performed_exercises', function (Blueprint $table) {
            $table->dropForeign(['performed_workout_id']);
        });

        DB::statement('ALTER TABLE performed_exercises MODIFY performed_workout_id BIGINT UNSIGNED NOT NULL');

        Schema::table('performed_exercises', function (Blueprint $table) {
            $table->foreign('performed_workout_id')
                ->references('id')
                ->on('performed_workouts')
                ->onDelete('cascade');
        });
    }
};

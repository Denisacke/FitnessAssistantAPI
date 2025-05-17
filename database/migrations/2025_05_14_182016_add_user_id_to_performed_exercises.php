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
        Schema::table('performed_exercises', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('performed_workout_id');

            // Optional: If you want to add the foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Populate user_id from performed_workout
        DB::statement('
        UPDATE performed_exercises
        JOIN performed_workouts ON performed_exercises.performed_workout_id = performed_workouts.id
        SET performed_exercises.user_id = performed_workouts.user_id
    ');
    }

    public function down(): void
    {
        Schema::table('performed_exercises', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }

};

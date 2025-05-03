<?php

use App\Http\Enums\UserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default(UserRole::REGULAR_USER);
            $table->string('sex');
            $table->double('weight');
            $table->double('height');
            $table->integer('age');
            $table->string('activity_level');
            $table->integer('recommended_calories');
            $table->integer('recommended_water_intake');
            $table->integer('bmi');
            $table->integer('body_fat')->nullable();
            $table->foreignId('trainer_id')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['trainer_id']);
            $table->dropColumn('trainer_id');
            $table->dropColumn(['role', 'sex', 'weight', 'age', 'activity_level', 'recommended_calories']);
        });
    }
};

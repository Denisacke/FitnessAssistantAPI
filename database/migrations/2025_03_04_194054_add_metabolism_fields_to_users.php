<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('sex', ['male', 'female'])->after('email');
            $table->double('weight')->after('sex');
            $table->integer('age')->after('weight');
            $table->string('activity_level')->after('age'); // Stored as a string, used as an Enum in the code
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['sex', 'weight', 'age', 'activity_level']);
        });
    }
};

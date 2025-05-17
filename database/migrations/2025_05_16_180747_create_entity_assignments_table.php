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
        Schema::create('entity_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->string('entity_type');
            $table->unsignedBigInteger('trainer_id');
            $table->unsignedBigInteger('client_id');

            $table->timestamps();

            $table->index(['entity_type', 'entity_id']);
            $table->index(['trainer_id']);
            $table->index(['client_id']);

//             $table->foreign('trainer_id')->references('id')->on('users')->onDelete('cascade');
//             $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');

            $table->unique(['entity_id', 'entity_type', 'client_id'], 'unique_assignment_per_entity_client');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entity_assignments');
    }
};

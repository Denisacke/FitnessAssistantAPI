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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('calories');
            $table->string('name', 150);
            $table->double('fat');
            $table->double('protein');
            $table->double('carbs');
            $table->double('fibre');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::drop('products');
    }
};

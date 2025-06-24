<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consumed_products', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->double('calories')->default(0);
            $table->double('protein')->default(0);
            $table->double('carbs')->default(0);
            $table->double('fats')->default(0);
        });

        Schema::table('consumed_products', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        Schema::table('consumed_products', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable()->change();
        });

        Schema::table('consumed_products', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->nullOnDelete();
        });

        Schema::table('consumed_recipes', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->double('calories')->default(0);
            $table->double('protein')->default(0);
            $table->double('carbs')->default(0);
            $table->double('fats')->default(0);
        });

        Schema::table('consumed_recipes', function (Blueprint $table) {
            $table->dropForeign(['recipe_id']);
        });

        Schema::table('consumed_recipes', function (Blueprint $table) {
            $table->unsignedBigInteger('recipe_id')->nullable()->change();
        });

        Schema::table('consumed_recipes', function (Blueprint $table) {
            $table->foreign('recipe_id')->references('id')->on('recipes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('consumed_products', function (Blueprint $table) {
            $table->dropColumn(['name', 'calories', 'protein', 'carbs', 'fats']);
            $table->dropForeign(['product_id']);
            $table->unsignedBigInteger('product_id')->nullable(false)->change();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::table('consumed_recipes', function (Blueprint $table) {
            $table->dropColumn(['name', 'calories', 'protein', 'carbs', 'fats']);
            $table->dropForeign(['recipe_id']);
            $table->unsignedBigInteger('recipe_id')->nullable(false)->change();
            $table->foreign('recipe_id')->references('id')->on('recipes')->onDelete('cascade');
        });
    }
};

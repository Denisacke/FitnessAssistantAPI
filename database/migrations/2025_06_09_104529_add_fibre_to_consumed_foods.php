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
        Schema::table('consumed_products', function (Blueprint $table) {
            $table->double('fibre')->default(0);
        });

        Schema::table('consumed_recipes', function (Blueprint $table) {
            $table->double('fibre')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consumed_products', function (Blueprint $table) {
            $table->dropColumn('fibre');
        });

        Schema::table('consumed_recipes', function (Blueprint $table) {
            $table->dropColumn('fibre');
        });
    }
};

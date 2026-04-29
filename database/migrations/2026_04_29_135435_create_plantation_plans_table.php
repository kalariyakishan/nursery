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
        Schema::create('plantation_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('polygon_coordinates');
            $table->string('method'); // grid, zigzag, random, custom
            $table->float('row_spacing');
            $table->float('plant_spacing');
            $table->integer('total_plants');
            $table->float('area');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plantation_plans');
    }
};

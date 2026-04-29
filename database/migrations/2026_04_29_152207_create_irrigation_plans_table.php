<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('irrigation_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plantation_plan_id')->constrained()->onDelete('cascade');
            $table->string('irrigation_type'); // drip, sprinkler, manual
            $table->json('water_source_coordinates')->nullable();
            $table->json('main_pipeline')->nullable();
            $table->json('sub_pipelines')->nullable();
            $table->float('total_main_pipe_length')->default(0);
            $table->float('total_sub_pipe_length')->default(0);
            $table->integer('drippers_per_plant')->default(1);
            $table->integer('total_drippers')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('irrigation_plans');
    }
};

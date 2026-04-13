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
        Schema::create('labour_entry_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('labour_entry_id')->constrained()->onDelete('cascade');
            $table->foreignId('worker_id')->constrained()->onDelete('cascade');
            $table->string('work_type')->nullable(); // planting, watering, etc.
            $table->string('attendance_type')->default('full'); // full, half, hours
            $table->decimal('hours', 4, 2)->nullable();
            $table->decimal('wage_amount', 10, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labour_entry_details');
    }
};

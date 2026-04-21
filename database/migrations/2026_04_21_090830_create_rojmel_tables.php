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
        Schema::create('rojmel_entries', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->enum('type', ['avak', 'javak']);
            $table->decimal('amount', 15, 2);
            $table->string('category')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
            
            $table->index('date');
        });

        Schema::create('daily_balances', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->decimal('total_avak', 15, 2)->default(0);
            $table->decimal('total_javak', 15, 2)->default(0);
            $table->decimal('closing_balance', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_balances');
        Schema::dropIfExists('rojmel_entries');
    }
};

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
        Schema::create('settlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')->constrained()->onDelete('cascade');
            $table->date('settlement_date');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_earnings', 12, 2);
            $table->decimal('total_advance', 12, 2);
            $table->decimal('payable_amount', 12, 2);
            $table->decimal('paid_amount', 12, 2);
            $table->string('payment_method')->nullable(); // Cash, Bank, UPI
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settlements');
    }
};

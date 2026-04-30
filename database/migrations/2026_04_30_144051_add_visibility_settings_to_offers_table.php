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
        Schema::table('offers', function (Blueprint $table) {
            $table->boolean('show_type')->default(true)->after('show_total');
            $table->boolean('show_size')->default(true)->after('show_type');
            $table->boolean('show_bag')->default(true)->after('show_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn(['show_type', 'show_size', 'show_bag']);
        });
    }
};

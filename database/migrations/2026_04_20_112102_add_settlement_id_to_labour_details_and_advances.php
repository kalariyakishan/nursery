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
        Schema::table('labour_entry_details', function (Blueprint $table) {
            $table->foreignId('settlement_id')->nullable()->constrained()->onDelete('set null');
        });

        Schema::table('advances', function (Blueprint $table) {
            $table->foreignId('settlement_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('labour_entry_details', function (Blueprint $table) {
            $table->dropForeign(['settlement_id']);
            $table->dropColumn('settlement_id');
        });

        Schema::table('advances', function (Blueprint $table) {
            $table->dropForeign(['settlement_id']);
            $table->dropColumn('settlement_id');
        });
    }
};

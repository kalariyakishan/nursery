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
        // 1. Add SoftDeletes to all relevant tables
        Schema::table('workers', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('labour_entries', function (Blueprint $table) {
            $table->softDeletes();
            $table->index('date');
        });
        Schema::table('labour_entry_details', function (Blueprint $table) {
            $table->softDeletes();
            $table->index('worker_id');
        });
        Schema::table('advances', function (Blueprint $table) {
            $table->softDeletes();
            $table->index(['worker_id', 'date']);
        });
        Schema::table('settlements', function (Blueprint $table) {
            $table->softDeletes();
            $table->index(['worker_id', 'settlement_date']);
        });

        // 2. Change onDelete('cascade') to onDelete('restrict') for better financial history protection
        Schema::table('labour_entry_details', function (Blueprint $table) {
            $table->dropForeign(['worker_id']);
            $table->foreign('worker_id')->references('id')->on('workers')->onDelete('restrict');
        });

        Schema::table('advances', function (Blueprint $table) {
            $table->dropForeign(['worker_id']);
            $table->foreign('worker_id')->references('id')->on('workers')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settlements', function (Blueprint $table) {
            $table->dropIndex(['worker_id', 'settlement_date']);
            $table->dropSoftDeletes();
        });
        Schema::table('advances', function (Blueprint $table) {
            $table->dropForeign(['worker_id']);
            $table->foreign('worker_id')->references('id')->on('workers')->onDelete('cascade');
            $table->dropIndex(['worker_id', 'date']);
            $table->dropSoftDeletes();
        });
        Schema::table('labour_entry_details', function (Blueprint $table) {
            $table->dropForeign(['worker_id']);
            $table->foreign('worker_id')->references('id')->on('workers')->onDelete('cascade');
            $table->dropIndex(['worker_id']);
            $table->dropSoftDeletes();
        });
        Schema::table('labour_entries', function (Blueprint $table) {
            $table->dropIndex(['date']);
            $table->dropSoftDeletes();
        });
        Schema::table('workers', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};

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
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('gst');
            $table->decimal('gst_percentage', 5, 2)->default(0)->after('discount');
            $table->decimal('gst_amount', 15, 2)->default(0)->after('gst_percentage');
            $table->decimal('cgst', 15, 2)->default(0)->after('gst_amount');
            $table->decimal('sgst', 15, 2)->default(0)->after('cgst');
            $table->string('gst_type')->default('exclusive')->after('sgst');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            //
        });
    }
};

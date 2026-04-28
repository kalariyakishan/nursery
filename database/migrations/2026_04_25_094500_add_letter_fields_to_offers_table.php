<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->string('reference_no')->nullable()->after('offer_no');
            $table->string('subject')->nullable()->after('reference_no');
            $table->string('greeting')->nullable()->after('subject');
            $table->text('intro_text')->nullable()->after('greeting');
        });
    }

    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn(['reference_no', 'subject', 'greeting', 'intro_text']);
        });
    }
};

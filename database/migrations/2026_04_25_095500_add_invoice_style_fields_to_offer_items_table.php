<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offer_items', function (Blueprint $table) {
            $table->string('type_of_plant')->nullable()->after('plant_name');
            $table->string('plant_size_feet')->nullable()->after('type_of_plant');
            $table->string('bag_size_inches')->nullable()->after('plant_size_feet');
        });
    }

    public function down(): void
    {
        Schema::table('offer_items', function (Blueprint $table) {
            $table->dropColumn(['type_of_plant', 'plant_size_feet', 'bag_size_inches']);
        });
    }
};

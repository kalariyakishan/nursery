<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            'આંબા કેસર - નુતન કલમ',
            'લીંબુ કલમ',
            'રાવણા - રોપ',
            'સેતૂર - રોપ',
            'રાયણ - રોપ',
            'જાંબુ કલમ (લાલ અને સફેદ)',
            'સરેલ રોપ',
            'મહુડો રોપ',
            'ચીકુ કલમ',
            'ફળસ - કલમ',
            'સીતાફળ - કલમ',
            'નારિયેળી બોના',
            'મોગરા રોપ',
            'બિલીપત્ર - રોપ',
            'જામફળ કલમ',
            'પુત્રંજીવા રોપ',
            'વાંસ રોપ',
            'બોરસલી રોપ',
            'ઉંબરૉ રોપ',
            'લીમડા રોપ',
            'કરંજ રોપ',
            'આસોપાલવ રોપ',
            'આમળા રોપ',
            'સિંદૂરી રોપ',
            'સરગવો રોપ',
            'ગરમાળો રોપ',
            'બદામ - રોપ',
            'ફાલસા - કલમ',
            'દાડમ - રોપ',
            'મોસંબી - કલમ',
            'કરેણ રોપ',
            'વડ રોપ',
            'શિશુ રોપ',
            'શરૂરોપ',
            'રેઇન ટ્રી રોપ',
            'પીપલ રોપ',
            'પબડી રોપ',
            'નિલગિરી રોપ',
            'બેહડા રોપ',
        ];

        foreach ($products as $productName) {
            $product = Product::firstOrCreate(['name' => $productName]);
            
            // Add a default variant if none exists
            if ($product->variants()->count() === 0) {
                $product->variants()->create([
                    'height' => '-',
                    'bag_size' => '-',
                    'price' => 0.00,
                ]);
            }
        }
    }
}

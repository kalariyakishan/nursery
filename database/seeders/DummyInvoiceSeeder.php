<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyInvoiceSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Invoice::truncate();
        InvoiceItem::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $customers = [
            ['name' => 'Kishan Kalariya', 'phone' => '9925575862'],
            ['name' => 'Viha Patel', 'phone' => '6355151302'],
            ['name' => 'Rajesh Bhai', 'phone' => '9876543210'],
            ['name' => 'Suresh Mehra', 'phone' => '8765432109'],
            ['name' => 'Amit Sharma', 'phone' => '7654321098'],
            ['name' => 'Mahesh Chavda', 'phone' => '9924455667'],
            ['name' => 'Jayesh Vaghela', 'phone' => '9426677889'],
            ['name' => 'Bharat Gadhvi', 'phone' => '9898112233'],
        ];

        $products = [
            ['name' => 'Mango (Kesar)', 'price' => 450.00, 'height' => '4-5 ft', 'bag' => '12x12'],
            ['name' => 'Coconut (Hybrid)', 'price' => 250.00, 'height' => '2-3 ft', 'bag' => '10x10'],
            ['name' => 'Chiku (Cricket Ball)', 'price' => 350.00, 'height' => '3-4 ft', 'bag' => '12x12'],
            ['name' => 'Lemon (Seedless)', 'price' => 150.00, 'height' => '2 ft', 'bag' => '8x8'],
            ['name' => 'Rose (Desi)', 'price' => 45.00, 'height' => '1 ft', 'bag' => '6x6'],
            ['name' => 'Bamboo Palm', 'price' => 850.00, 'height' => '5 ft', 'bag' => '14x14'],
            ['name' => 'Areca Palm', 'price' => 650.00, 'height' => '4 ft', 'bag' => '12x12'],
            ['name' => 'Jade Plant', 'price' => 120.00, 'height' => '0.5 ft', 'bag' => '5x5'],
            ['name' => 'Bonsai Ficus', 'price' => 1200.00, 'height' => '2 ft', 'bag' => '16x16'],
            ['name' => 'Money Plant', 'price' => 80.00, 'height' => '1 ft', 'bag' => '6x6'],
        ];

        foreach ($customers as $customerData) {
            $subtotal = 0;
            $itemsCount = rand(5, 10); // User requested 5+10 records (interpreted as 5-10 records per invoice)
            $selectedKeys = (array)array_rand($products, $itemsCount);
            
            $invoiceItemsData = [];
            foreach ($selectedKeys as $index) {
                $qty = rand(1, 10);
                $p = $products[$index];
                $itemTotal = $p['price'] * $qty;
                $subtotal += $itemTotal;
                
                $invoiceItemsData[] = [
                    'product_name' => $p['name'],
                    'height' => $p['height'],
                    'bag_size' => $p['bag'],
                    'price' => $p['price'],
                    'quantity' => $qty,
                    'total' => $itemTotal,
                ];
            }

            $discount = floor($subtotal * (rand(0, 15) / 100));
            $finalTotal = $subtotal - $discount;

            $invoice = Invoice::create([
                'customer_name' => $customerData['name'],
                'phone' => $customerData['phone'],
                'address' => 'Gadu, Dist Junagadh, Gujarat',
                'subtotal' => $subtotal,
                'discount' => $discount,
                'gst' => 0,
                'total' => $finalTotal,
                'notes' => 'સેમ્પલ બિલ (Sample Bill)',
                'created_at' => now()->subDays(rand(0, 30)),
            ]);

            foreach ($invoiceItemsData as $item) {
                $invoice->items()->create($item);
            }
        }
    }
}

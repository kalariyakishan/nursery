<?php

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

DB::beginTransaction();
try {
    $invoice = Invoice::with('items')->find(6);
    if (!$invoice) {
        echo "Invoice #6 not found.\n";
        exit;
    }

    $newItems = [
        ['product_name' => 'તુલસી - છોડ', 'price' => 20, 'quantity' => 50],
        ['product_name' => 'બારમાસી - છોડ', 'price' => 30, 'quantity' => 15],
        ['product_name' => 'જામફળ કલમ', 'price' => 180, 'quantity' => 6],
    ];

    foreach ($newItems as $item) {
        $total = $item['price'] * $item['quantity'];
        $invoice->items()->create([
            'product_name' => $item['product_name'],
            'price' => $item['price'],
            'quantity' => $item['quantity'],
            'total' => $total,
            'height' => '-',
            'bag_size' => '-',
        ]);
    }

    // Recalculate totals
    $newSubtotal = $invoice->items()->sum('total');
    $discount = $invoice->discount;
    $newTotal = $newSubtotal - $discount;

    $invoice->update([
        'subtotal' => $newSubtotal,
        'total' => $newTotal,
    ]);

    DB::commit();
    echo "Success: Added 5 items to Invoice #6. New Total: ₹" . number_format($newTotal, 2) . "\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}

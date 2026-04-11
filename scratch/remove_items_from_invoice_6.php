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

    // Delete the last 1 item
    $lastTwoItems = $invoice->items()->latest('id')->limit(1)->get();
    foreach ($lastTwoItems as $item) {
        $item->delete();
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
    echo "Success: Removed 2 items from Invoice #6. New Total: ₹" . number_format($newTotal, 2) . "\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}

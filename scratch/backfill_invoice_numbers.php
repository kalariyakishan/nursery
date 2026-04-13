<?php

use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$invoices = Invoice::whereNull('invoice_no')->orderBy('id', 'asc')->get();

echo "Backfilling " . $invoices->count() . " invoices...\n";

foreach ($invoices as $invoice) {
    // We can't just save() because booted()'s creating event only fires on new records.
    // And booted() logic depends on existing records.
    
    $date = $invoice->created_at ?: now();
    $year = $date->format('Y');
    
    $latest = Invoice::whereYear('created_at', $year)
        ->whereNotNull('invoice_no')
        ->orderBy('invoice_no', 'desc')
        ->first();

    if (!$latest) {
        $number = 1;
    } else {
        $parts = explode('-', $latest->invoice_no);
        $number = (int)end($parts) + 1;
    }

    $invoice->invoice_no = 'INV-' . $year . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    $invoice->save();
    
    echo "Updated Invoice #{$invoice->id} to {$invoice->invoice_no}\n";
}

echo "Done!\n";

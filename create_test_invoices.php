<?php
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Customer;

$customer = Customer::first();
if (!$customer) {
    $customer = Customer::create([
        'name' => 'Testing Layout Customer',
        'mobile' => '9999999999',
        'address' => 'Test Avenue',
        'place_of_supply' => 'Gujarat (24)'
    ]);
}

for ($i = 1; $i <= 50; $i++) {
    $invoice = new Invoice();
    $invoice->customer_id = $customer->id;
    $invoice->customer_name = $customer->name;
    $invoice->customer_mobile = $customer->mobile;
    $invoice->place_of_supply = $customer->place_of_supply ?? 'Gujarat (24)';
    $invoice->invoice_date = now()->format('Y-m-d');
    $invoice->discount = 0;
    
    $year = date('Y');
    $lastInvoice = Invoice::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
    $nextNumber = $lastInvoice && preg_match('/-(\d{4})$/', $lastInvoice->invoice_number, $matches) ? intval($matches[1]) + 1 : 1;
    $invoice->invoice_number = '#INV-' . $year . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    $invoice->extra_notes = "Testing invoice with EXACTLY $i items.";
    
    $invoice->subtotal = 0;
    $invoice->cgst_amount = 0;
    $invoice->sgst_amount = 0;
    $invoice->grand_total = 0;
    $invoice->save();
    
    $subtotal = 0;
    for ($j = 1; $j <= $i; $j++) {
        $qty = 2;
        $rate = 100;
        $amount = $qty * $rate;
        $subtotal += $amount;
        
        InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'product_name' => "Plant Item #$j for layout test",
            'height' => "3 Ft",
            'bag_size' => "8x8",
            'quantity' => $qty,
            'rate' => $rate,
            'amount' => $amount
        ]);
    }
    
    $invoice->subtotal = $subtotal;
    // Assuming some GST structure is in place, let's just use simple math.
    $invoice->cgst_amount = 0;
    $invoice->sgst_amount = 0;
    $invoice->grand_total = $subtotal;
    $invoice->save();
}

echo "Successfully created 50 test invoices!\n";

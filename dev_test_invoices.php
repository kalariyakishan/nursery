<?php
use App\Models\Invoice;
use App\Models\InvoiceItem;

for ($i = 1; $i <= 50; $i++) {
    $invoice = new Invoice();
    $invoice->customer_name = "Layout Tester $i";
    $invoice->phone = '9999999999';
    $invoice->address = 'Gujarat (24)';
    $invoice->discount = 0;
    
    // invoice_no is handled by Model booted event natively
    $invoice->notes = "Testing invoice with EXACTLY $i items.";
    
    $invoice->subtotal = 0;
    $invoice->gst_percentage = 0;
    $invoice->gst_amount = 0;
    $invoice->cgst = 0;
    $invoice->sgst = 0;
    $invoice->gst_type = 'None';
    $invoice->total = 0;
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
            'price' => $rate,
            'total' => $amount
        ]);
    }
    
    $invoice->subtotal = $subtotal;
    $invoice->total = $subtotal;
    $invoice->save();
}

echo "Successfully created 50 test invoices!\n";

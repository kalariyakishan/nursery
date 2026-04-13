<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceFactory> */
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'customer_name',
        'phone',
        'address',
        'subtotal',
        'discount',
        'gst_percentage',
        'gst_amount',
        'cgst',
        'sgst',
        'gst_type',
        'total',
        'notes',
        'created_at'
    ];

    protected static function booted()
    {
        static::creating(function ($invoice) {
            $date = $invoice->created_at ?: now();
            $year = $date->format('Y');
            
            $latest = static::whereYear('created_at', $year)
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
        });
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}

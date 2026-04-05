<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceFactory> */
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'phone',
        'address',
        'subtotal',
        'discount',
        'gst',
        'total',
        'notes',
        'created_at'
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_no',
        'reference_no',
        'subject',
        'greeting',
        'intro_text',
        'customer_name',
        'phone',
        'address',
        'subtotal',
        'discount',
        'total',
        'terms',
        'show_total',
        'show_type',
        'show_size',
        'show_bag',
        'created_at',
    ];

    protected static function booted(): void
    {
        static::creating(function (Offer $offer): void {
            if (!empty($offer->offer_no)) {
                return;
            }

            $date = $offer->created_at ?: now();
            $year = $date->format('Y');

            $latest = static::whereYear('created_at', $year)
                ->whereNotNull('offer_no')
                ->orderByDesc('id')
                ->first();

            $number = 1;
            if ($latest && str_contains($latest->offer_no, '-')) {
                $parts = explode('-', $latest->offer_no);
                $number = ((int) end($parts)) + 1;
            }

            $offer->offer_no = 'OFF-' . $year . '-' . str_pad((string) $number, 4, '0', STR_PAD_LEFT);
        });
    }

    public function items()
    {
        return $this->hasMany(OfferItem::class);
    }
}

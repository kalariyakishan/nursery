<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_id',
        'plant_name',
        'type_of_plant',
        'plant_size_feet',
        'bag_size_inches',
        'variant',
        'quantity',
        'rate',
        'total',
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RojmelEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'settlement_id',
        'date',
        'type',
        'amount',
        'category',
        'description'
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'opening_balance',
        'total_avak',
        'total_javak',
        'closing_balance'
    ];

    protected $casts = [
        'date' => 'date',
        'opening_balance' => 'decimal:2',
        'total_avak' => 'decimal:2',
        'total_javak' => 'decimal:2',
        'closing_balance' => 'decimal:2'
    ];
}

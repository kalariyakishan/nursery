<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Settlement extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'worker_id',
        'settlement_date',
        'start_date',
        'end_date',
        'total_earnings',
        'total_advance',
        'payable_amount',
        'paid_amount',
        'payment_method',
        'notes'
    ];

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    public function labourDetails()
    {
        return $this->hasMany(LabourEntryDetail::class);
    }

    public function advances()
    {
        return $this->hasMany(Advance::class);
    }
}

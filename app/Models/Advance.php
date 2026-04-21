<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advance extends Model
{
    use SoftDeletes;
    protected $fillable = ['worker_id', 'date', 'amount', 'note', 'settlement_id'];

    public function settlement()
    {
        return $this->belongsTo(Settlement::class);
    }

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }
}

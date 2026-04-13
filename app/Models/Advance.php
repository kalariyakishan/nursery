<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advance extends Model
{
    protected $fillable = ['worker_id', 'date', 'amount', 'note'];

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }
}

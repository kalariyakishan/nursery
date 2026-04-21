<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabourEntryDetail extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'labour_entry_id',
        'worker_id',
        'work_type',
        'attendance_type',
        'hours',
        'wage_amount',
        'notes',
        'settlement_id'
    ];

    public function settlement()
    {
        return $this->belongsTo(Settlement::class);
    }

    public function entry()
    {
        return $this->belongsTo(LabourEntry::class, 'labour_entry_id');
    }

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }
}

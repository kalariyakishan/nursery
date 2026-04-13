<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabourEntryDetail extends Model
{
    protected $fillable = [
        'labour_entry_id',
        'worker_id',
        'work_type',
        'attendance_type',
        'hours',
        'wage_amount',
        'notes'
    ];

    public function entry()
    {
        return $this->belongsTo(LabourEntry::class, 'labour_entry_id');
    }

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }
}

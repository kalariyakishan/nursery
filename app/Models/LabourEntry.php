<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabourEntry extends Model
{
    protected $fillable = ['date', 'total_workers', 'total_amount'];

    public function details()
    {
        return $this->hasMany(LabourEntryDetail::class);
    }
}

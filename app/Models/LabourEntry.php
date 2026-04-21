<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabourEntry extends Model
{
    use SoftDeletes;
    protected $fillable = ['date', 'total_workers', 'total_amount'];

    public function details()
    {
        return $this->hasMany(LabourEntryDetail::class);
    }
}

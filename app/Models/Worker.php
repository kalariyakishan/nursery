<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    protected $fillable = ['name', 'phone', 'default_wage'];

    public function attendance()
    {
        return $this->hasMany(LabourEntryDetail::class);
    }

    public function advances()
    {
        return $this->hasMany(Advance::class);
    }
}

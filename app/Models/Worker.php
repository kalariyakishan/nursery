<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Worker extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'phone', 'default_wage'];

    public function attendance()
    {
        return $this->hasMany(LabourEntryDetail::class);
    }

    public function advances()
    {
        return $this->hasMany(Advance::class);
    }

    public function settlements()
    {
        return $this->hasMany(Settlement::class);
    }
}

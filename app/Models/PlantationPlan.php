<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlantationPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'polygon_coordinates',
        'method',
        'row_spacing',
        'plant_spacing',
        'total_plants',
        'area'
    ];

    protected $casts = [
        'polygon_coordinates' => 'array',
        'row_spacing' => 'float',
        'plant_spacing' => 'float',
        'total_plants' => 'integer',
        'area' => 'float',
    ];

    public function irrigationPlan()
    {
        return $this->hasOne(IrrigationPlan::class);
    }
}

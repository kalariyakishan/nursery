<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IrrigationPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'plantation_plan_id',
        'irrigation_type',
        'water_source_coordinates',
        'main_pipeline',
        'sub_pipelines',
        'total_main_pipe_length',
        'total_sub_pipe_length',
        'drippers_per_plant',
        'total_drippers',
    ];

    protected $casts = [
        'water_source_coordinates' => 'array',
        'main_pipeline' => 'array',
        'sub_pipelines' => 'array',
    ];

    public function plantationPlan()
    {
        return $this->belongsTo(PlantationPlan::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleSheet extends Model
{
    protected $fillable = [
        'google_integration_id',
        'sheet_type',
        'sheet_id',
        'sheet_url',
        'last_synced_at',
    ];

    protected $casts = [
        'last_synced_at' => 'datetime',
    ];

    public function integration()
    {
        return $this->belongsTo(GoogleIntegration::class, 'google_integration_id');
    }
}

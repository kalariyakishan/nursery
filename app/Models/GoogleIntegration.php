<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleIntegration extends Model
{
    protected $fillable = [
        'user_id',
        'access_token',
        'refresh_token',
        'expires_at',
        'google_email',
        'sheet_id',
        'sheet_url',
        'auto_sync',
        'last_synced_at',
    ];

    protected $casts = [
        'access_token' => 'encrypted',
        'refresh_token' => 'encrypted',
        'expires_at' => 'datetime',
        'last_synced_at' => 'datetime',
        'auto_sync' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sheets()
    {
        return $this->hasMany(GoogleSheet::class);
    }
}

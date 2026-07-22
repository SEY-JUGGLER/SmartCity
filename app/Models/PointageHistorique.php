<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PointageHistorique extends Model
{
    protected $table = 'pointages';

    protected $fillable = [
        'user_id', 'action', 'pointer', 'disponible', 'heure_action',
    ];

    protected $casts = [
        'pointer' => 'boolean',
        'disponible' => 'boolean',
        'heure_action' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

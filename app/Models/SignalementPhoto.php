<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SignalementPhoto extends Model
{
    protected $fillable = [
        'signalement_id', 'path', 'type', 'description',
    ];

    public function signalement() { return $this->belongsTo(Signalement::class); }
}

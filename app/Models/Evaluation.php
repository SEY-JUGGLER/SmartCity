<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'signalement_id', 'user_id', 'note', 'commentaire', 'probleme_resolu',
    ];

    protected $casts = [
        'probleme_resolu' => 'boolean',
    ];

    public function signalement() { return $this->belongsTo(Signalement::class); }
    public function user()        { return $this->belongsTo(User::class); }
}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    public const NOTE_LABELS = [
        1 => 'non satisfait',
        2 => 'peu satisfaisant',
        3 => 'satisfaisant',
        4 => 'très satisfaisant',
        5 => 'excellent',
    ];

    public const NOTE_SCORES = [
        'non satisfait' => 1,
        'peu satisfaisant' => 2,
        'satisfaisant' => 3,
        'très satisfaisant' => 4,
        'excellent' => 5,
    ];

    protected $fillable = [
        'signalement_id', 'user_id', 'note', 'commentaire', 'probleme_resolu',
    ];

    protected $casts = [
        'note' => 'string',
        'probleme_resolu' => 'boolean',
    ];

    protected function noteScore(): Attribute
    {
        return Attribute::make(
            get: fn () => self::NOTE_SCORES[$this->note] ?? 0,
        );
    }

    public function signalement() { return $this->belongsTo(Signalement::class); }
    public function user()        { return $this->belongsTo(User::class); }
}

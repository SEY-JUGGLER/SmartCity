<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materiel extends Model
{
    protected $fillable = [
        'nom', 'description', 'categorie', 'statut', 'agent_id', 'date_attribution',
    ];

    protected $casts = [
        'date_attribution' => 'date',
    ];

    public function agent() { return $this->belongsTo(User::class); }
}

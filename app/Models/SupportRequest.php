<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportRequest extends Model
{
    protected $fillable = [
        'agent_id', 'type', 'description', 'statut', 'traite_par', 'date_traitement',
    ];

    protected $casts = [
        'date_traitement' => 'datetime',
    ];

    public function agent()    { return $this->belongsTo(User::class, 'agent_id'); }
    public function traitePar(){ return $this->belongsTo(User::class, 'traite_par'); }
}

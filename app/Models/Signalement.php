<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Signalement extends Model
{
    protected $fillable = [
        'position', 'latitude', 'longitude', 'description', 'priorite',
        'statut', 'dateSignalement', 'date_resolution', 'photodoc',
        'user_id', 'zone_id', 'categorie_id',
        'commentaire_agent', 'commentaire_admin',
    ];

    protected $casts = [
        'dateSignalement'  => 'date',
        'date_resolution'  => 'datetime',
    ];

    public function citoyen()          { return $this->belongsTo(User::class, 'user_id'); }
    public function zone()             { return $this->belongsTo(Zone::class); }
    public function attribution()      { return $this->hasOne(Attribution::class); }
    public function categorie()        { return $this->belongsTo(Categorie::class); }
    public function photos()           { return $this->hasMany(SignalementPhoto::class); }
    public function evaluation()       { return $this->hasOne(Evaluation::class); }

    const STATUTS = ['enAttente', 'enCours', 'terminer', 'rejeter'];
}

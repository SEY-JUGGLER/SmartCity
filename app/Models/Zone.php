<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $fillable = ['nomZone', 'superficie', 'nombreHabitant', 'latitude', 'longitude', 'commune_id'];

    public function commune()      { return $this->belongsTo(Commune::class); }
    public function users()        { return $this->hasMany(User::class); }
    public function signalements() { return $this->hasMany(Signalement::class); }
}
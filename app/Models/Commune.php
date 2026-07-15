<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    protected $fillable = ['nom', 'description'];

    public function zones()
    {
        return $this->hasMany(Zone::class);
    }

    public function agents()
    {
        return $this->hasManyThrough(User::class, Zone::class, 'commune_id', 'zone_id')
            ->where('role', 'AGENT');
    }

    public function signalements()
    {
        return $this->hasManyThrough(Signalement::class, Zone::class, 'commune_id', 'zone_id');
    }
}

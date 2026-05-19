<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $fillable = ['nom', 'description', 'priorite', 'active'];

    public function signalements()
    {
        return $this->hasMany(Signalement::class);
    }
}

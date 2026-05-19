<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Localisation extends Model
{
    protected $fillable = ['latitude', 'longitude', 'dateHeure', 'user_id'];

    protected $casts = ['dateHeure' => 'datetime'];

    public function user() { return $this->belongsTo(User::class); }
}
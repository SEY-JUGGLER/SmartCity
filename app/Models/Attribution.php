<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribution extends Model
{
    protected $fillable = [
        'dateHeureAttribution', 'signalement_id', 'agent_id', 'admin_id'
    ];

    protected $casts = ['dateHeureAttribution' => 'datetime'];

    public function signalement() { return $this->belongsTo(Signalement::class); }
    public function agent()       { return $this->belongsTo(User::class, 'agent_id'); }
    public function admin()       { return $this->belongsTo(User::class, 'admin_id'); }
}
<?php
namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser, HasName
{
    use Notifiable;

    protected $fillable = [
        'name', 'prenom', 'email', 'password',
        'age', 'localite', 'photoProfi', 'role',
        'compteBloque', 'disponible', 'actif',
        'pointer', 'heurePointage', 'zone_id', 'telephone',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'compteBloque'  => 'boolean',
        'disponible'    => 'boolean',
        'actif'         => 'boolean',
        'pointer'       => 'boolean',
        'heurePointage' => 'datetime',
    ];

    /** Filament v5 — nom affiché dans le menu et l'avatar */
    public function getFilamentName(): string
    {
        $full = trim(($this->prenom ?? '') . ' ' . ($this->name ?? ''));
        return $full ?: ($this->email ?? 'Utilisateur');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($this->compteBloque || ! $this->actif) {
            return false;
        }

        return $panel->getId() === 'admin' && $this->role === 'ADMIN';
    }

    public function zone()               { return $this->belongsTo(Zone::class); }
    public function attributionsAgent() { return $this->hasMany(Attribution::class, 'agent_id'); }
    public function signalements()      { return $this->hasMany(Signalement::class, 'user_id'); }
    public function localisations()     { return $this->hasMany(Localisation::class); }
    public function evaluations()       { return $this->hasMany(Evaluation::class); }
    public function supportRequests()   { return $this->hasMany(SupportRequest::class, 'agent_id'); }
    public function notificationPreferences()
    {
        return $this->hasOne(NotificationPreference::class);
    }

    public function isAdmin(): bool   { return $this->role === 'ADMIN'; }
    public function isAgent(): bool   { return $this->role === 'AGENT'; }
    public function isCitoyen(): bool { return $this->role === 'CITOYEN'; }
}

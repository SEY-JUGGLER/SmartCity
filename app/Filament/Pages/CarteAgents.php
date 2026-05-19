<?php

namespace App\Filament\Pages;

use App\Models\Localisation;
use App\Models\User;
use BackedEnum;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use UnitEnum;

class CarteAgents extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-map';
    protected static ?string $navigationLabel = 'Carte des agents';
    protected static ?string $title = 'Position des agents en temps réel';
    protected static UnitEnum|string|null $navigationGroup = 'Zones & Missions';
    protected static ?int $navigationSort = 6;
    protected string $view = 'filament.pages.carte-agents';

    public function getAgentsPosition(): Collection
    {
        return User::where('role', 'AGENT')->where('actif', true)
            ->with(['localisations' => fn($q) => $q->latest()->take(1)])
            ->get()
            ->filter(fn($u) => $u->localisations->isNotEmpty())
            ->map(fn($u) => [
                'id'       => $u->id,
                'prenom'   => $u->prenom,
                'nom'      => $u->name,
                'lat'      => (float) $u->localisations->first()->latitude,
                'lng'      => (float) $u->localisations->first()->longitude,
                'date'     => $u->localisations->first()->dateHeure?->diffForHumans() ?? '—',
                'disponible' => $u->disponible,
                'zone'     => $u->zone?->nomZone ?? '—',
            ])->values();
    }
}

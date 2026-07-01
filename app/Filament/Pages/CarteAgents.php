<?php

namespace App\Filament\Pages;

use App\Models\Localisation;
use App\Models\Signalement;
use App\Models\User;
use App\Models\Zone;
use BackedEnum;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use UnitEnum;

class CarteAgents extends Page
{
    protected static BackedEnum|string|null $navigationIcon  = 'heroicon-o-map';
    protected static ?string               $navigationLabel = 'Carte temps réel';
    protected static ?string               $title           = 'Carte des agents et signalements en temps réel';
    protected static UnitEnum|string|null  $navigationGroup = 'Zones & Missions';
    protected static ?int                  $navigationSort  = 6;
    protected string                       $view            = 'filament.pages.carte-agents';

    public string $filtreStatut   = 'actifs';
    public string $rechercheAgent = '';
    public bool   $montrerAgents = true;
    public bool   $montrerSignalements = true;
    public bool   $sidebarOuverte = true;

    public function toggleSidebar(): void
    {
        $this->sidebarOuverte = !$this->sidebarOuverte;
        $this->dispatch('sidebarToggled', ouverte: $this->sidebarOuverte);
    }

    public function getAgentsPosition(): Collection
    {
        return User::where('role', 'AGENT')->where('actif', true)
            ->when($this->rechercheAgent, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->rechercheAgent}%")
                  ->orWhere('prenom', 'like', "%{$this->rechercheAgent}%")
                  ->orWhere('email', 'like', "%{$this->rechercheAgent}%")
                  ->orWhereHas('zone', fn ($q) => $q->where('nomZone', 'like', "%{$this->rechercheAgent}%"));
            }))
            ->with(['localisations' => fn ($q) => $q->latest()->limit(1), 'zone'])
            ->get()
            ->filter(fn ($u) => $u->localisations->isNotEmpty() || ($u->pointer && $u->zone?->latitude && $u->zone?->longitude))
            ->map(fn ($u) => [
                'id'         => $u->id,
                'prenom'     => $u->prenom,
                'nom'        => $u->name,
                'lat'        => (float) ($u->localisations->first()?->latitude ?? $u->zone?->latitude),
                'lng'        => (float) ($u->localisations->first()?->longitude ?? $u->zone?->longitude),
                'date'       => $u->localisations->first()?->dateHeure?->diffForHumans() ?? '—',
                'disponible' => (bool) $u->disponible,
                'zone'       => $u->zone?->nomZone ?? '—',
                'zone_id'    => $u->zone_id,
                'pointer'    => (bool) $u->pointer,
            ])->values();
    }

    public function getSignalementsPosition(): Collection
    {
        $query = Signalement::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('latitude', '!=', 0)
            ->where('longitude', '!=', 0)
            ->with(['categorie', 'citoyen', 'attribution.agent']);

        match ($this->filtreStatut) {
            'attente' => $query->where('statut', 'enAttente'),
            'cours'   => $query->where('statut', 'enCours'),
            'actifs'  => $query->whereIn('statut', ['enAttente', 'enCours']),
            default   => $query->whereIn('statut', ['enAttente', 'enCours', 'terminer']),
        };

        return $query->latest()->get()->map(fn ($s) => [
            'id'          => $s->id,
            'lat'         => (float) $s->latitude,
            'lng'         => (float) $s->longitude,
            'statut'      => $s->statut,
            'priorite'    => $s->priorite,
            'description' => Str::limit($s->description, 60),
            'position'    => $s->position,
            'categorie'   => $s->categorie?->nom ?? '—',
            'citoyen'     => trim(($s->citoyen?->prenom ?? '') . ' ' . ($s->citoyen?->name ?? '')),
            'agent'       => $s->attribution?->agent
                ? trim(($s->attribution->agent->prenom ?? '') . ' ' . ($s->attribution->agent->name ?? ''))
                : null,
            'date'        => $s->created_at?->diffForHumans() ?? '—',
        ])->values();
    }

    public function getStats(): array
    {
        $agentsActifs    = User::where('role', 'AGENT')->where('actif', true)->where('disponible', true)->count();
        $agentsOccupes   = User::where('role', 'AGENT')->where('actif', true)->where('disponible', false)->count();
        $sigEnAttente    = Signalement::where('statut', 'enAttente')->count();
        $sigEnCours      = Signalement::where('statut', 'enCours')->count();
        $agentsLocalises = User::where('role', 'AGENT')->where('actif', true)
            ->where(function ($q) {
                $q->whereHas('localisations')
                  ->orWhere(function ($q) {
                      $q->where('pointer', true)
                        ->whereHas('zone', fn ($q) => $q->whereNotNull('latitude')->whereNotNull('longitude'));
                  });
            })->count();

        return compact('agentsActifs', 'agentsOccupes', 'sigEnAttente', 'sigEnCours', 'agentsLocalises');
    }

    public function getZones(): Collection
    {
        return Zone::orderBy('nomZone')->get(['id', 'nomZone']);
    }

    public function affecterZone(int $agentId, int $zoneId): void
    {
        $agent = User::where('role', 'AGENT')->findOrFail($agentId);
        $agent->update(['zone_id' => $zoneId ?: null]);
        $this->refreshMapData();
    }

    public function refreshMapData(): void
    {
        $this->dispatch('mapDataRefreshed',
            agents:        $this->getAgentsPosition()->toArray(),
            signalements:  $this->getSignalementsPosition()->toArray(),
        );
    }

    public function setFiltreStatut(string $statut): void
    {
        $this->filtreStatut = $statut;
        $this->refreshMapData();
    }
}

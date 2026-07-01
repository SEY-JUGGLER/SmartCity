<?php

namespace App\Services;

use App\Models\Attribution;
use App\Models\Signalement;
use App\Models\User;
use App\Models\Zone;

class AgentStatsService
{
    private ?array $agentCache = null;
    private ?float $tempsMoyenCache = null;
    private ?float $acceptationCache = null;
    private ?int $zonesCritiquesCache = null;

    public function getAgentStats(): array
    {
        if ($this->agentCache !== null) {
            return $this->agentCache;
        }

        $base = User::where('role', 'AGENT');
        $total = (clone $base)->count();
        $disponibles = (clone $base)
            ->where('actif', true)
            ->where('disponible', true)
            ->where('pointer', true)
            ->where('heurePointage', '>', now()->subHours(12))
            ->count();
        $occupes = (clone $base)
            ->where('actif', true)
            ->where('disponible', false)
            ->count();
        $absents = (clone $base)
            ->where('actif', true)
            ->where(function ($q) {
                $q->where('pointer', false)
                  ->orWhere('heurePointage', '<=', now()->subHours(12));
            })
            ->count();
        $inactifs = (clone $base)->where('actif', false)->count();

        $this->agentCache = compact('total', 'disponibles', 'occupes', 'absents', 'inactifs');

        return $this->agentCache;
    }

    public function getTempsMoyenTraitement(): float
    {
        if ($this->tempsMoyenCache !== null) {
            return $this->tempsMoyenCache;
        }

        $signalements = Signalement::where('statut', 'terminer')
            ->whereNotNull('created_at')
            ->whereNotNull('updated_at')
            ->get(['created_at', 'updated_at']);

        if ($signalements->isEmpty()) {
            $this->tempsMoyenCache = 0.0;

            return $this->tempsMoyenCache;
        }

        $totalHours = $signalements->sum(
            fn (Signalement $signalement) => $signalement->created_at->diffInHours($signalement->updated_at)
        );

        $this->tempsMoyenCache = round($totalHours / $signalements->count(), 2);

        return $this->tempsMoyenCache;
    }

    public function getTempsMoyenAcceptation(): float
    {
        if ($this->acceptationCache !== null) {
            return $this->acceptationCache;
        }

        $attributions = Attribution::query()
            ->whereNotNull('dateHeureAttribution')
            ->with(['signalement:id,created_at,statut'])
            ->get();

        $durations = $attributions
            ->filter(fn (Attribution $attribution) => $attribution->signalement && $attribution->signalement->statut !== 'rejeter')
            ->map(fn (Attribution $attribution) => $attribution->signalement->created_at->diffInMinutes($attribution->dateHeureAttribution));

        if ($durations->isEmpty()) {
            $this->acceptationCache = 0.0;

            return $this->acceptationCache;
        }

        $this->acceptationCache = round(($durations->avg() ?? 0) / 60, 1);

        return $this->acceptationCache;
    }

    public function getZonesCritiques(): int
    {
        if ($this->zonesCritiquesCache !== null) {
            return $this->zonesCritiquesCache;
        }

        $this->zonesCritiquesCache = Zone::whereHas('signalements', fn ($q) => $q->whereIn('statut', ['enAttente', 'enCours']), '>', 5)->count();

        return $this->zonesCritiquesCache;
    }
}

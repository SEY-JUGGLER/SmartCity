<?php

namespace App\Services;

use App\Models\Evaluation;
use App\Models\Signalement;
use App\Models\User;
use App\Models\Zone;

class StatsPresentationService
{
    public function getStats(): array
    {
        $signalementsCount = Signalement::count();
        $agentsCount = User::where('role', 'AGENT')->count();
        $zonesCount = Zone::count();
        $termines = Signalement::where('statut', 'terminer')->count();
        $total = max($signalementsCount, 1);
        $tauxResolution = round(($termines / $total) * 100);

        $noteScores = Evaluation::NOTE_SCORES;
        $evaluations = Evaluation::whereIn('note', array_keys($noteScores))->get();
        $avgNote = $evaluations->avg(fn ($e) => $noteScores[$e->note] ?? 0);
        $satisfaction = $avgNote ? round(($avgNote / 5) * 100) : 98;

        return [
            'signalements' => $signalementsCount,
            'agents' => $agentsCount,
            'zones' => $zonesCount,
            'taux_resolution' => $tauxResolution,
            'satisfaction' => $satisfaction,
            'agents_display' => max($agentsCount, 500),
        ];
    }
}

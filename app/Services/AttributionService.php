<?php

namespace App\Services;

use App\Models\Signalement;
use App\Models\User;
use Illuminate\Support\Collection;

class AttributionService
{
    public function findNearestAgent(Signalement $signalement): ?User
    {
        $ranked = $this->rankAgentsByDistance($signalement);
        $first  = $ranked->first();

        if (! $first || $first['distance_km'] === null) {
            return null;
        }

        return $first['user'];
    }

    /**
     * Retourne tous les agents disponibles triés par distance croissante.
     * Chaque élément : ['user' => User, 'distance_km' => float|null, 'source' => 'gps'|'zone']
     *
     * Position de l'agent (par priorité) :
     *  1. Dernière localisation GPS (table localisations)
     *  2. Coordonnées du centre de la zone assignée
     */
    public function rankAgentsByDistance(Signalement $signalement): Collection
    {
        $agents = User::where('role', 'AGENT')
            ->where('actif', true)
            ->where('disponible', true)
            ->with([
                'localisations' => fn ($q) => $q->latest('dateHeure')->take(1),
                'zone',
            ])
            ->get();

        if (! $signalement->latitude || ! $signalement->longitude) {
            return $agents->map(fn ($u) => [
                'user'        => $u,
                'distance_km' => null,
                'source'      => $u->localisations->first() ? 'gps' : 'zone',
            ]);
        }

        return $agents
            ->map(function ($u) use ($signalement) {
                $loc = $u->localisations->first();

                // Priorité 1 : GPS temps réel
                if ($loc?->latitude !== null && $loc?->longitude !== null) {
                    return [
                        'user'        => $u,
                        'distance_km' => $this->haversine(
                            (float) $signalement->latitude,
                            (float) $signalement->longitude,
                            (float) $loc->latitude,
                            (float) $loc->longitude,
                        ),
                        'source' => 'gps',
                    ];
                }

                // Priorité 2 : centre de la zone assignée
                if ($u->zone?->latitude !== null && $u->zone?->longitude !== null) {
                    return [
                        'user'        => $u,
                        'distance_km' => $this->haversine(
                            (float) $signalement->latitude,
                            (float) $signalement->longitude,
                            (float) $u->zone->latitude,
                            (float) $u->zone->longitude,
                        ),
                        'source' => 'zone',
                    ];
                }

                return ['user' => $u, 'distance_km' => null, 'source' => 'zone'];
            })
            ->sortBy(fn ($item) => $item['distance_km'] ?? PHP_FLOAT_MAX)
            ->values();
    }

    /**
     * Formule de Haversine — retourne la distance en kilomètres.
     */
    public function haversine(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $R    = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a    = sin($dLat / 2) ** 2
              + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;

        return $R * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}

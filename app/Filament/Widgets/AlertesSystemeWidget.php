<?php

namespace App\Filament\Widgets;

use App\Models\Signalement;
use App\Models\Zone;
use App\Services\AgentStatsService;
use Filament\Widgets\Widget;

class AlertesSystemeWidget extends Widget
{
    protected static ?int $sort = 6;
    protected static bool $isLazy = false;
    protected int|string|array $columnSpan = ['default' => 'full'];
    protected string $view = 'filament.widgets.alertes-systeme';
    protected ?string $pollingInterval = '30s';

    public function getAlertes(): array
    {
        $alertes = [];

        $critiques = Signalement::where('priorite', 'critique')
            ->where('statut', 'enAttente')
            ->where('created_at', '<', now()->subHours(2))
            ->count();

        if ($critiques > 0) {
            $alertes[] = [
                'type' => 'danger',
                'titre' => "{$critiques} critique(s) non assigné(s)",
                'detail' => 'En attente depuis plus de 2h — action immédiate requise',
                'icon' => 'exclamation-triangle',
            ];
        }

        $agents = app(AgentStatsService::class)->getAgentStats();
        $disponibles = $agents['disponibles'];
        $enAttente = Signalement::where('statut', 'enAttente')->count();

        if ($disponibles === 0 && $enAttente > 0) {
            $alertes[] = [
                'type' => 'danger',
                'titre' => 'Aucun agent disponible',
                'detail' => "{$enAttente} signalement(s) en attente sans agent disponible",
                'icon' => 'user-minus',
            ];
        } elseif ($disponibles < 2 && $enAttente > 5) {
            $alertes[] = [
                'type' => 'warning',
                'titre' => 'Sous-effectif agents',
                'detail' => "{$disponibles} agent(s) pour {$enAttente} signalements",
                'icon' => 'exclamation-circle',
            ];
        }

        Zone::withCount(['signalements as actifs_count' => fn($q) => $q->whereIn('statut', ['enAttente', 'enCours'])])
            ->whereHas('signalements', fn($q) => $q->whereIn('statut', ['enAttente', 'enCours']), '>', 5)
            ->get()
            ->each(fn($z) => $alertes[] = [
                'type' => 'warning',
                'titre' => "Zone : {$z->nomZone}",
                'detail' => "{$z->actifs_count} signalements actifs",
                'icon' => 'map-pin',
            ]);

        if ($agents['absents'] > 0) {
            $alertes[] = [
                'type' => 'info',
                'titre' => "{$agents['absents']} agent(s) non pointé(s)",
                'detail' => 'Vérifier les présences du jour',
                'icon' => 'clock',
            ];
        }

        if (empty($alertes)) {
            $alertes[] = [
                'type' => 'success',
                'titre' => 'Système opérationnel',
                'detail' => 'Aucune alerte active',
                'icon' => 'check-circle',
            ];
        }

        return $alertes;
    }
}

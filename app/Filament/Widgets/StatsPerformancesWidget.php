<?php

namespace App\Filament\Widgets;

use App\Services\AgentStatsService;
use App\Models\Signalement;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsPerformancesWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected static bool $isLazy = false;
    protected int|string|array $columnSpan = 'full';
    protected ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $svc = app(AgentStatsService::class);
        $agents = $svc->getAgentStats();

        $totalSignalements = Signalement::count() ?: 1;
        $termines = Signalement::where('statut', 'terminer')->count();
        $rejetes = Signalement::where('statut', 'rejeter')->count();
        $tauxResolution = round(($termines / $totalSignalements) * 100, 1);
        $tauxRefus = round(($rejetes / $totalSignalements) * 100, 1);
        $tempsMoyenTraitement = $svc->getTempsMoyenTraitement();
        $averageAcceptationHours = $svc->getTempsMoyenAcceptation();
        $zonesCritiques = $svc->getZonesCritiques();

        return [
            Stat::make('Taux résolution', $tauxResolution . '%')
                ->description('Signalements résolus')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color($tauxResolution >= 70 ? 'success' : ($tauxResolution >= 40 ? 'warning' : 'danger')),

            Stat::make('Taux refus', $tauxRefus . '%')
                ->description('Signalements rejetés')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color($tauxRefus <= 10 ? 'success' : ($tauxRefus <= 25 ? 'warning' : 'danger')),

            Stat::make('Temps moyen', round($tempsMoyenTraitement, 1) . 'h')
                ->description('Création → résolution')
                ->descriptionIcon('heroicon-m-clock')
                ->color($tempsMoyenTraitement <= 24 ? 'success' : 'warning'),

            Stat::make('Acceptance', $averageAcceptationHours . 'h')
                ->description('Création → attribution')
                ->descriptionIcon('heroicon-m-arrow-right-circle')
                ->color($averageAcceptationHours <= 1 ? 'success' : ($averageAcceptationHours <= 4 ? 'warning' : 'danger')),

            Stat::make('Disponibles', $agents['disponibles'])
                ->description("/ {$agents['total']} agents")
                ->descriptionIcon('heroicon-m-hand-raised')
                ->color($agents['disponibles'] >= 3 ? 'success' : ($agents['disponibles'] >= 1 ? 'warning' : 'danger')),

            Stat::make('Occupés', $agents['occupes'])
                ->description('En intervention')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('warning'),

            Stat::make('Absents / Inactifs', ($agents['absents'] + $agents['inactifs']))
                ->description("{$agents['absents']} non pointés, {$agents['inactifs']} inactifs")
                ->descriptionIcon('heroicon-m-user-minus')
                ->color($agents['absents'] + $agents['inactifs'] > 0 ? 'danger' : 'success'),

            Stat::make('Zones critiques', $zonesCritiques)
                ->description('> 5 signalements actifs')
                ->descriptionIcon('heroicon-m-map-pin')
                ->color($zonesCritiques === 0 ? 'success' : 'danger'),
        ];
    }
}

<?php

namespace App\Filament\Widgets;

use App\Models\Signalement;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsSignalementsWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static bool $isLazy = false;
    protected int|string|array $columnSpan = 'full';
    protected ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $sparkline = fn(string $statut) => collect(range(6, 0))
            ->map(fn($d) => Signalement::whereDate('dateSignalement', today()->subDays($d))
                ->where('statut', $statut)->count())
            ->toArray();

        $globalSpark = collect(range(6, 0))
            ->map(fn($d) => Signalement::whereDate('dateSignalement', today()->subDays($d))->count())
            ->toArray();

        $critiques = Signalement::where('priorite', 'critique')
            ->whereIn('statut', ['enAttente', 'enCours'])->count();

        return [
            Stat::make('Total', Signalement::count())
                ->description('Tous statuts confondus')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary')
                ->chart($globalSpark),

            Stat::make('En attente', Signalement::where('statut', 'enAttente')->count())
                ->description('À traiter')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->chart($sparkline('enAttente')),

            Stat::make('En cours', Signalement::where('statut', 'enCours')->count())
                ->description('En traitement')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('info'),

            Stat::make('Terminés', Signalement::where('statut', 'terminer')->count())
                ->description('Résolus')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart($sparkline('terminer')),

            Stat::make('Critiques', $critiques)
                ->description('Haute priorité non résolus')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($critiques > 0 ? 'danger' : 'success'),

            Stat::make('Aujourd\'hui', Signalement::whereDate('dateSignalement', today())->count())
                ->description('Nouveaux signalements')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),
        ];
    }
}

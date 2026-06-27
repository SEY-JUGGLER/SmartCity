<?php

namespace App\Filament\Pages;
use UnitEnum;
use BackedEnum;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static UnitEnum|string|null $navigationGroup = 'Tableau de bord';
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Tableau de bord';
    protected static ?string $title = 'Tableau de bord';

    public function getColumns(): int | array
    {
        return 1;
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\StatsSignalementsWidget::class,
            \App\Filament\Widgets\StatsPerformancesWidget::class,
            \App\Filament\Widgets\EvolutionChartWidget::class,
            \App\Filament\Widgets\AgentsDonutWidget::class,
            \App\Filament\Widgets\ActiviteRecenteWidget::class,
            \App\Filament\Widgets\AlertesSystemeWidget::class,
            \App\Filament\Widgets\PointageWidget::class,
            \App\Filament\Widgets\ClassificationsWidget::class,
        ];
    }
}

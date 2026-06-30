<?php

namespace App\Filament\Widgets;

use App\Models\Signalement;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class EvolutionChartWidget extends Widget
{
    protected static ?int $sort = 3;
    protected static bool $isLazy = false;
    protected int|string|array $columnSpan = ['default' => 'full'];
    protected string $view = 'filament.widgets.evolution-chart';
    protected ?string $pollingInterval = '60s';

    public function getData(): array
    {
        $start = Carbon::today()->subDays(29);
        $end = Carbon::today();

        $labels = [];
        for ($i = 29; $i >= 0; $i--) {
            $labels[] = Carbon::today()->subDays($i)->format('d/m');
        }

        $fill = fn($collection) => collect()
            ->pad(30, 0)
            ->map(fn($_, $i) => $collection->get(Carbon::today()->subDays(29 - $i)->toDateString(), 0))
            ->toArray();

        $countByJour = fn(string $statut) => Signalement::whereBetween('dateSignalement', [$start, $end])
            ->where('statut', $statut)
            ->selectRaw('DATE("dateSignalement") as jour, COUNT(*) as total')
            ->groupBy('jour')
            ->pluck('total', 'jour')
            ->pipe($fill);

        return [
            'labels' => $labels,
            'attente' => $countByJour('enAttente'),
            'cours' => $countByJour('enCours'),
            'termines' => $countByJour('terminer'),
            'rejetes' => $countByJour('rejeter'),
        ];
    }
}

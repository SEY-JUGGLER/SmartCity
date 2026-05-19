<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Services\AgentStatsService;
use Filament\Widgets\Widget;

class AgentsDonutWidget extends Widget
{
    protected static ?int $sort = 4;
    protected static bool $isLazy = false;
    protected int|string|array $columnSpan = ['md' => 2, 'xl' => 5];
    protected string $view = 'filament.widgets.agents-donut';
    protected ?string $pollingInterval = '30s';

    public function getAgentStats(): array
    {
        return app(AgentStatsService::class)->getAgentStats();
    }

    public function getTopAgents(): array
    {
        return User::where('role', 'AGENT')
            ->where('actif', true)
            ->withCount([
                'attributionsAgent as missions' => fn($q) =>
                    $q->whereHas('signalement', fn($s) =>
                        $s->where('statut', 'terminer')->whereMonth('updated_at', now()->month)
                    ),
            ])
            ->orderByDesc('missions')
            ->limit(5)
            ->get()
            ->map(fn($a) => ['nom' => $a->prenom . ' ' . $a->nom, 'missions' => $a->missions])
            ->toArray();
    }
}

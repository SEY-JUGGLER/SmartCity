<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Services\AgentStatsService;
use Filament\Widgets\Widget;

class PointageWidget extends Widget
{
    protected static ?int $sort = 7;
    protected static bool $isLazy = false;
    protected int|string|array $columnSpan = ['default' => 'full'];
    protected string $view = 'filament.widgets.pointage';
    protected ?string $pollingInterval = '30s';

    public function getData(): array
    {
        $agents      = app(AgentStatsService::class)->getAgentStats();
        $totalAgents = $agents['total'];
        $inactifs    = $agents['inactifs'];
        $base        = User::where('role', 'AGENT');
        $presents    = (clone $base)->where('actif', true)->where('pointer', true)->count();
        $nonPointes  = (clone $base)->where('pointer', false)->count();

        $avgHours = null;
        $pointageToday = (clone $base)
            ->where('pointer', true)
            ->whereNotNull('heurePointage')
            ->whereDate('heurePointage', today())
            ->get();

        if ($pointageToday->isNotEmpty()) {
            $count = $totalSec = 0;
            foreach ($pointageToday as $agent) {
                $diff = now()->diffInSeconds($agent->heurePointage);
                if ($diff >= 0 && $diff <= 86400) { $totalSec += $diff; $count++; }
            }
            if ($count > 0) $avgHours = round($totalSec / $count / 3600, 1);
        }

        $tauxPresence = $totalAgents > 0 ? round(($presents / $totalAgents) * 100) : 0;

        return array_merge(
            compact('totalAgents', 'presents', 'nonPointes', 'inactifs', 'avgHours', 'tauxPresence'),
            ['absents' => $agents['absents']]
        );
    }
}

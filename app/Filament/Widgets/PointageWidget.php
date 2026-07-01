<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Services\AgentStatsService;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

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

        $expiredAt = now()->subHours(12);

        $base = User::where('role', 'AGENT');
        $presents = (clone $base)
            ->where('actif', true)
            ->where('pointer', true)
            ->where('heurePointage', '>', $expiredAt)
            ->count();
        $nonPointes = (clone $base)
            ->where('actif', true)
            ->where(function ($q) use ($expiredAt) {
                $q->where('pointer', false)
                  ->orWhere('heurePointage', '<=', $expiredAt);
            })
            ->count();

        $avgHours = null;
        $pointageActif = (clone $base)
            ->where('actif', true)
            ->where('pointer', true)
            ->where('heurePointage', '>', $expiredAt)
            ->whereNotNull('heurePointage')
            ->get();

        $pointageDetails = [];
        if ($pointageActif->isNotEmpty()) {
            $count = $totalSec = 0;
            foreach ($pointageActif as $agent) {
                $diff = now()->diffInSeconds($agent->heurePointage);
                $hours = round($diff / 3600, 1);
                if ($diff >= 0 && $diff <= 43200) {
                    $totalSec += $diff;
                    $count++;
                }
                $pointageDetails[] = [
                    'nom'   => ($agent->prenom ?? '') . ' ' . ($agent->name ?? ''),
                    'heure' => $agent->heurePointage instanceof Carbon
                        ? $agent->heurePointage->format('H:i')
                        : optional($agent->heurePointage)->format('H:i'),
                    'depuis'=> $hours,
                ];
            }
            if ($count > 0) $avgHours = round($totalSec / $count / 3600, 1);
        }

        $tauxPresence = $totalAgents > 0 ? round(($presents / $totalAgents) * 100) : 0;

        return array_merge(
            compact('totalAgents', 'presents', 'nonPointes', 'inactifs', 'avgHours', 'tauxPresence', 'pointageDetails'),
            ['absents' => $agents['absents']]
        );
    }
}

<?php

namespace App\Filament\Widgets;

use App\Models\ActivityLog;
use Filament\Widgets\Widget;

class ActiviteRecenteWidget extends Widget
{
    protected static ?int $sort = 5;
    protected static bool $isLazy = false;
    protected int|string|array $columnSpan = ['default' => 'full'];
    protected string $view = 'filament.widgets.activite-recente';
    protected ?string $pollingInterval = '30s';

    public function getActivites(): array
    {
        return ActivityLog::latest()->limit(20)->get()
            ->map(function ($log) {
                [$icon, $color] = match (true) {
                    str_contains($log->action, 'created')   => ['clock', 'warning'],
                    str_contains($log->action, 'attributed') => ['arrow-path', 'info'],
                    str_contains($log->action, 'status_changed') => ['arrow-path-rounded-square', 'info'],
                    str_contains($log->action, 'valide')    => ['check-circle', 'success'],
                    str_contains($log->action, 'refuse')    => ['x-circle', 'danger'],
                    str_contains($log->action, 'generated') => ['document-chart-bar', 'primary'],
                    default                                 => ['bell', 'gray'],
                };

                $actionLabel = match ($log->action) {
                    'signalement.created'       => 'Nouveau signalement soumis',
                    'signalement.attributed'    => 'Signalement attribué',
                    'signalement.status_changed'=> 'Statut modifié',
                    'support.valide'            => 'Demande support validée',
                    'support.refuse'            => 'Demande support refusée',
                    'rapport.generated'         => 'Rapport généré',
                    default                     => $log->action,
                };

                return [
                    'id'      => $log->id,
                    'label'   => $actionLabel,
                    'sub'     => $log->description,
                    'citoyen' => $log->user_name,
                    'time'    => $log->created_at->diffForHumans(),
                    'statut'  => $log->action,
                    'color'   => $color,
                    'icon'    => $icon,
                ];
            })->toArray();
    }
}

<?php

namespace App\Filament\Widgets;

use App\Models\Signalement;
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
        return Signalement::with(['citoyen', 'attribution.agent', 'categorie'])
            ->orderByDesc('dateSignalement')->orderByDesc('created_at')->limit(10)->get()
            ->map(function ($s) {
                return [
                    'id' => $s->id,
                    'label' => match ($s->statut) {
                        'enAttente' => 'Nouveau signalement soumis',
                        'enCours' => 'Prise en charge par ' . ($s->attribution?->agent?->prenom ?? 'un agent'),
                        'terminer' => 'Intervention terminée avec succès',
                        'rejeter' => 'Signalement rejeté',
                        default => 'Mise à jour',
                    },
                    'sub' => ($s->categorie?->nom ?? '—') . ' · ' . $s->position,
                    'citoyen' => $s->citoyen?->prenom . ' ' . $s->citoyen?->nom,
                    'time' => $s->updated_at->diffForHumans(),
                    'statut' => $s->statut,
                    'color' => match ($s->statut) {
                        'enAttente' => 'text-amber-600 bg-amber-50 dark:bg-amber-900/20 ring-1 ring-amber-200 dark:ring-amber-700',
                        'enCours' => 'text-cyan-600 bg-cyan-50 dark:bg-cyan-900/20 ring-1 ring-cyan-200 dark:ring-cyan-700',
                        'terminer' => 'text-emerald-600 bg-emerald-50 dark:bg-emerald-900/20 ring-1 ring-emerald-200 dark:ring-emerald-700',
                        'rejeter' => 'text-red-600 bg-red-50 dark:bg-red-900/20 ring-1 ring-red-200 dark:ring-red-700',
                        default => 'text-gray-500 bg-gray-50 ring-1 ring-gray-200',
                    },
                    'icon' => match ($s->statut) {
                        'enAttente' => 'clock',
                        'enCours' => 'arrow-path',
                        'terminer' => 'check-circle',
                        'rejeter' => 'x-circle',
                        default => 'bell',
                    },
                ];
            })->toArray();
    }
}

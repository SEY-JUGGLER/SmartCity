<?php

namespace App\Filament\Pages;

use App\Models\Evaluation;
use App\Models\Signalement;
use App\Models\User;
use App\Models\Attribution;
use App\Services\ClassificationService;
use BackedEnum;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use UnitEnum;

class ClassementsAdmin extends Page
{
    protected static BackedEnum|string|null $navigationIcon  = 'heroicon-o-trophy';
    protected static ?string               $navigationLabel = 'Classements';
    protected static ?string               $title           = 'Classements & Performances';
    protected static UnitEnum|string|null  $navigationGroup = 'Utilisateurs';
    protected static ?int                  $navigationSort  = 10;
    protected string                       $view            = 'filament.pages.classements-admin';

    public string $onglet       = 'agents';
    public string $triAgent     = 'missions';
    public string $triCitoyen   = 'signalements';
    public string $recherche    = '';

    public function getStatsTop(): array
    {
        $agents = User::where('role', 'AGENT');
        $citoyens = User::where('role', 'CITOYEN');

        return [
            'total_agents'   => (clone $agents)->count(),
            'actifs'         => (clone $agents)->where('actif', true)->count(),
            'disponibles'    => (clone $agents)->where('disponible', true)->count(),
            'total_citoyens' => (clone $citoyens)->count(),
            'moy_taux'       => round(User::where('role', 'AGENT')->get()->map(fn ($a) => (float) Evaluation::whereHas('signalement.attribution', fn ($q) => $q->where('agent_id', $a->id))->avg('note') ?? 0)->avg(), 1),
        ];
    }

    public function getAgentsRanking(): Collection
    {
        return User::where('role', 'AGENT')
            ->when($this->recherche, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->recherche}%")
                  ->orWhere('prenom', 'like', "%{$this->recherche}%")
                  ->orWhere('email', 'like', "%{$this->recherche}%")
                  ->orWhereHas('zone', fn ($q) => $q->where('nomZone', 'like', "%{$this->recherche}%"));
            }))
            ->withCount(['attributionsAgent as missions_terminees' => fn ($q) =>
                $q->whereHas('signalement', fn ($q) => $q->where('statut', 'terminer'))
            ])
            ->withCount(['attributionsAgent as total_missions'])
            ->get()
            ->map(function ($agent) {
                $noteMoyenne = (float) (Evaluation::whereHas(
                    'signalement.attribution',
                    fn ($q) => $q->where('agent_id', $agent->id)
                )->avg('note') ?? 0);

                $avgReaction = 0;
                try {
                    $row = DB::table('attributions as a')
                        ->join('signalements as s', 's.id', '=', 'a.signalement_id')
                        ->where('a.agent_id', $agent->id)
                        ->where('s.statut', 'terminer')
                        ->whereNotNull('s.date_resolution')
                        ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, a.dateHeureAttribution, s.date_resolution)) as avg_hours')
                        ->first();
                    $avgReaction = round((float) ($row->avg_hours ?? 0), 1);
                } catch (\Throwable) {}

                $tauxCompletion = $agent->total_missions > 0
                    ? round(($agent->missions_terminees / $agent->total_missions) * 100)
                    : 0;

                return (object) [
                    'id'               => $agent->id,
                    'nom'              => trim(($agent->prenom ?? '') . ' ' . ($agent->name ?? '')),
                    'email'            => $agent->email,
                    'photo'            => $agent->photoProfi,
                    'zone'             => $agent->zone?->nomZone ?? '—',
                    'missions_terminees'=> (int) $agent->missions_terminees,
                    'total_missions'   => (int) $agent->total_missions,
                    'note_moyenne'     => round($noteMoyenne, 1),
                    'taux_completion'  => $tauxCompletion,
                    'avg_reaction'     => $avgReaction,
                    'classification'   => ClassificationService::classifierAgent($agent->id),
                ];
            })
            ->sortByDesc(match ($this->triAgent) {
                'note'       => fn ($a) => $a->note_moyenne,
                'taux'       => fn ($a) => $a->taux_completion,
                'reaction'   => fn ($a) => -$a->avg_reaction, // ascending (faster = better)
                default      => fn ($a) => $a->missions_terminees,
            })
            ->values();
    }

    public function getCitoyensRanking(): Collection
    {
        return User::where('role', 'CITOYEN')
            ->when($this->recherche, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->recherche}%")
                  ->orWhere('prenom', 'like', "%{$this->recherche}%")
                  ->orWhere('email', 'like', "%{$this->recherche}%");
            }))
            ->get()
            ->map(function ($user) {
                $total      = Signalement::where('user_id', $user->id)->count();
                $termines   = Signalement::where('user_id', $user->id)->where('statut', 'terminer')->count();
                $rejetes    = Signalement::where('user_id', $user->id)->where('statut', 'rejeter')->count();
                $evaluations= Evaluation::where('user_id', $user->id)->count();
                $tauxVal    = $total > 0 ? round(($termines / $total) * 100) : 0;

                return (object) [
                    'id'             => $user->id,
                    'nom'            => trim(($user->prenom ?? '') . ' ' . ($user->name ?? '')),
                    'email'          => $user->email,
                    'photo'          => $user->photoProfi,
                    'signalements'   => $total,
                    'termines'       => $termines,
                    'rejetes'        => $rejetes,
                    'evaluations'    => $evaluations,
                    'taux_validation'=> $tauxVal,
                    'engagement'     => $total + $evaluations,
                    'classification' => ClassificationService::classifierCitoyen($user->id),
                ];
            })
            ->sortByDesc(match ($this->triCitoyen) {
                'evaluations' => fn ($c) => $c->evaluations,
                'taux'        => fn ($c) => $c->taux_validation,
                'engagement'  => fn ($c) => $c->engagement,
                default       => fn ($c) => $c->signalements,
            })
            ->values();
    }

    public function getAgentClassStats(): array
    {
        return ClassificationService::countAgentClasses();
    }

    public function getCitoyenClassStats(): array
    {
        return ClassificationService::countCitoyenClasses();
    }
}

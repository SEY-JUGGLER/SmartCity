<?php

namespace App\Filament\Pages;

use App\Models\Attribution;
use App\Models\Evaluation;
use App\Models\Signalement;
use App\Models\User;
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

        $allEvals = Evaluation::whereHas('signalement.attribution', fn ($q) => $q->whereHas('signalement', fn ($q) => $q->whereNotNull('id')))
            ->get();
        $moyTaux = $allEvals->count() > 0
            ? $allEvals->avg(fn ($e) => Evaluation::NOTE_SCORES[$e->note] ?? 0)
            : 0;

        return [
            'total_agents'   => (clone $agents)->count(),
            'actifs'         => (clone $agents)->where('actif', true)->count(),
            'disponibles'    => (clone $agents)->where('disponible', true)->count(),
            'total_citoyens' => (clone $citoyens)->count(),
            'moy_taux'       => round($moyTaux, 1),
        ];
    }

    public function getAgentsRanking(): Collection
    {
        $agentIds = User::where('role', 'AGENT')
            ->when($this->recherche, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->recherche}%")
                  ->orWhere('prenom', 'like', "%{$this->recherche}%")
                  ->orWhere('email', 'like', "%{$this->recherche}%")
                  ->orWhereHas('zone', fn ($q) => $q->where('nomZone', 'like', "%{$this->recherche}%"));
            }))
            ->pluck('id');

        $reactionAvg = DB::table('attributions as a')
            ->join('signalements as s', 's.id', '=', 'a.signalement_id')
            ->where('s.statut', 'terminer')
            ->whereNotNull('s.date_resolution')
            ->selectRaw('a.agent_id, AVG(' . \App\Helpers\DatabaseHelper::diffInHoursSql('a.dateHeureAttribution', 's.date_resolution') . ') as avg_hours')
            ->whereIn('a.agent_id', $agentIds)
            ->groupBy('a.agent_id')
            ->pluck('avg_hours', 'agent_id');

        $noteAvg = Evaluation::whereHas('signalement.attribution', fn ($q) => $q->whereIn('agent_id', $agentIds))
            ->selectRaw('signalement_id, note')
            ->get()
            ->groupBy(fn ($e) => optional(optional($e->signalement)->attribution)->agent_id)
            ->map(function ($evals) {
                $scores = $evals->map(fn ($e) => Evaluation::NOTE_SCORES[$e->note] ?? 0);
                return round($scores->avg(), 1);
            });

        $classifications = $agentIds->mapWithKeys(fn ($id) => [$id => ClassificationService::classifierAgent($id)]);

        return User::whereIn('id', $agentIds)
            ->withCount(['attributionsAgent as missions_terminees' => fn ($q) =>
                $q->whereHas('signalement', fn ($q) => $q->where('statut', 'terminer'))
            ])
            ->withCount(['attributionsAgent as total_missions'])
            ->with('zone')
            ->get()
            ->map(function ($agent) use ($reactionAvg, $noteAvg, $classifications) {
                $totalMissions = (int) $agent->total_missions;
                return (object) [
                    'id'                => $agent->id,
                    'nom'               => trim(($agent->prenom ?? '') . ' ' . ($agent->name ?? '')),
                    'email'             => $agent->email,
                    'photo'             => $agent->photoProfi,
                    'zone'              => $agent->zone?->nomZone ?? '—',
                    'missions_terminees' => (int) $agent->missions_terminees,
                    'total_missions'    => $totalMissions,
                    'note_moyenne'      => $noteAvg->get($agent->id, 0),
                    'taux_completion'   => $totalMissions > 0 ? round(($agent->missions_terminees / $totalMissions) * 100) : 0,
                    'avg_reaction'      => round((float) ($reactionAvg->get($agent->id) ?? 0), 1),
                    'classification'    => $classifications[$agent->id] ?? ['label' => '—', 'color' => 'slate', 'emoji' => ''],
                ];
            })
            ->sortByDesc(match ($this->triAgent) {
                'note'       => fn ($a) => $a->note_moyenne,
                'taux'       => fn ($a) => $a->taux_completion,
                'reaction'   => fn ($a) => -$a->avg_reaction,
                default      => fn ($a) => $a->missions_terminees,
            })
            ->values();
    }

    public function getCitoyensRanking(): Collection
    {
        $citoyenIds = User::where('role', 'CITOYEN')
            ->when($this->recherche, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->recherche}%")
                  ->orWhere('prenom', 'like', "%{$this->recherche}%")
                  ->orWhere('email', 'like', "%{$this->recherche}%");
            }))
            ->pluck('id');

        $signalementCounts = Signalement::whereIn('user_id', $citoyenIds)
            ->selectRaw('user_id, COUNT(*) as total, SUM(CASE WHEN statut = ? THEN 1 ELSE 0 END) as termines, SUM(CASE WHEN statut = ? THEN 1 ELSE 0 END) as rejetes', ['terminer', 'rejeter'])
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        $evaluationCounts = Evaluation::whereIn('user_id', $citoyenIds)
            ->selectRaw('user_id, COUNT(*) as total')
            ->groupBy('user_id')
            ->pluck('total', 'user_id');

        $classifications = $citoyenIds->mapWithKeys(fn ($id) => [$id => ClassificationService::classifierCitoyen($id)]);

        $users = User::whereIn('id', $citoyenIds)->get();

        return $users
            ->map(function ($user) use ($signalementCounts, $evaluationCounts, $classifications) {
                $sigStats = $signalementCounts->get($user->id);
                $total    = (int) ($sigStats->total ?? 0);
                $termines = (int) ($sigStats->termines ?? 0);
                $rejetes  = (int) ($sigStats->rejetes ?? 0);
                $evaluations = (int) ($evaluationCounts->get($user->id) ?? 0);

                return (object) [
                    'id'              => $user->id,
                    'nom'             => trim(($user->prenom ?? '') . ' ' . ($user->name ?? '')),
                    'email'           => $user->email,
                    'photo'           => $user->photoProfi,
                    'signalements'    => $total,
                    'termines'        => $termines,
                    'rejetes'         => $rejetes,
                    'evaluations'     => $evaluations,
                    'taux_validation' => $total > 0 ? round(($termines / $total) * 100) : 0,
                    'engagement'      => $total + $evaluations,
                    'classification'  => $classifications[$user->id] ?? ['label' => '—', 'color' => 'slate', 'emoji' => ''],
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

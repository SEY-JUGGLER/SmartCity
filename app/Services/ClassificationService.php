<?php

namespace App\Services;

use App\Models\Attribution;
use App\Models\Evaluation;
use App\Models\Signalement;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ClassificationService
{
    const AGENT_CLASSES = [
        'gardien'     => ['key' => 'gardien',     'label' => 'Gardien de la ville',       'color' => 'emerald', 'emoji' => '🛡️',  'desc' => 'Taux élevé de missions réussies'],
        'heros'       => ['key' => 'heros',       'label' => 'Héros de la réactivité',    'color' => 'blue',    'emoji' => '⚡',   'desc' => 'Intervient rapidement après affectation'],
        'pilier'      => ['key' => 'pilier',      'label' => 'Pilier de la collaboration','color' => 'amber',   'emoji' => '🤝',   'desc' => 'Volume élevé de missions, pilier de l\'équipe'],
        'exemplaire'  => ['key' => 'exemplaire',  'label' => 'Agent exemplaire',          'color' => 'violet',  'emoji' => '⭐',   'desc' => 'Respect des procédures et communication transparente'],
        'accompagner' => ['key' => 'accompagner', 'label' => 'Agent à accompagner',       'color' => 'red',     'emoji' => '📋',   'desc' => 'Faible taux de missions réalisées'],
        'actif'       => ['key' => 'actif',       'label' => 'Agent actif',               'color' => 'slate',   'emoji' => '👤',   'desc' => 'Agent de terrain'],
    ];

    const CITOYEN_CLASSES = [
        'ambassadeur'  => ['key' => 'ambassadeur',  'label' => 'Ambassadeur de la propreté', 'color' => 'emerald', 'emoji' => '🌟', 'desc' => 'Signale régulièrement et de manière fiable'],
        'sentinelle'   => ['key' => 'sentinelle',   'label' => 'Sentinelle du quartier',     'color' => 'blue',    'emoji' => '👁️',  'desc' => 'Alerte rapidement sur les problèmes dans sa zone'],
        'modele'       => ['key' => 'modele',       'label' => 'Citoyen modèle',             'color' => 'violet',  'emoji' => '🏆', 'desc' => 'Taux élevé de signalements validés et participation'],
        'observateur'  => ['key' => 'observateur',  'label' => 'Observateur engagé',         'color' => 'amber',   'emoji' => '📊', 'desc' => 'Consulte et relaie les informations'],
        'sensibiliser' => ['key' => 'sensibiliser', 'label' => 'Citoyen à sensibiliser',     'color' => 'red',     'emoji' => '⚠️', 'desc' => 'Signalements abusifs ou non conformes'],
        'actif'        => ['key' => 'actif',        'label' => 'Citoyen actif',              'color' => 'slate',   'emoji' => '👤', 'desc' => 'Membre de la communauté'],
    ];

    public static function classifierAgent(int $agentId): array
    {
        $totalMissions = Attribution::where('agent_id', $agentId)->count();
        $missionsTerminees = Attribution::where('agent_id', $agentId)
            ->whereHas('signalement', fn ($q) => $q->where('statut', 'terminer'))
            ->count();
        $noteMoyenne = (float) (Evaluation::whereHas(
            'signalement.attribution',
            fn ($q) => $q->where('agent_id', $agentId)
        )->avg('note') ?? 0);

        $tauxCompletion = $totalMissions > 0 ? $missionsTerminees / $totalMissions : 0;

        $avgReactionHours = 0;
        if ($totalMissions >= 1) {
            try {
                $row = DB::table('attributions as a')
                    ->join('signalements as s', 's.id', '=', 'a.signalement_id')
                    ->where('a.agent_id', $agentId)
                    ->where('s.statut', 'terminer')
                    ->whereNotNull('s.date_resolution')
                    ->selectRaw('AVG(EXTRACT(EPOCH FROM (s.date_resolution - a.dateHeureAttribution)) / 3600) as avg_hours')
                    ->first();
                $avgReactionHours = (float) ($row->avg_hours ?? 0);
            } catch (\Throwable) {
                $avgReactionHours = 0;
            }
        }

        if ($totalMissions >= 3 && $tauxCompletion < 0.3) {
            return self::AGENT_CLASSES['accompagner'];
        }
        if ($missionsTerminees >= 5 && $noteMoyenne >= 4.0) {
            return self::AGENT_CLASSES['exemplaire'];
        }
        if ($missionsTerminees >= 5 && $tauxCompletion >= 0.7) {
            return self::AGENT_CLASSES['gardien'];
        }
        if ($totalMissions >= 3 && $avgReactionHours > 0 && $avgReactionHours <= 4) {
            return self::AGENT_CLASSES['heros'];
        }
        if ($totalMissions >= 10 && $tauxCompletion >= 0.5) {
            return self::AGENT_CLASSES['pilier'];
        }

        return self::AGENT_CLASSES['actif'];
    }

    public static function classifierCitoyen(int $citoyenId): array
    {
        $total = Signalement::where('user_id', $citoyenId)->count();
        $termines = Signalement::where('user_id', $citoyenId)->where('statut', 'terminer')->count();
        $rejetes = Signalement::where('user_id', $citoyenId)->where('statut', 'rejeter')->count();
        $evaluations = Evaluation::where('user_id', $citoyenId)->count();

        $tauxValidation = $total > 0 ? $termines / $total : 0;
        $tauxRejet = $total > 0 ? $rejetes / $total : 0;

        if ($rejetes >= 2 && $tauxRejet >= 0.3) {
            return self::CITOYEN_CLASSES['sensibiliser'];
        }
        if ($total >= 5 && $tauxValidation >= 0.7 && $evaluations >= 2) {
            return self::CITOYEN_CLASSES['modele'];
        }
        if ($total >= 5 && $tauxValidation >= 0.6) {
            return self::CITOYEN_CLASSES['ambassadeur'];
        }
        if ($total >= 3) {
            return self::CITOYEN_CLASSES['sentinelle'];
        }
        if ($evaluations >= 2) {
            return self::CITOYEN_CLASSES['observateur'];
        }

        return self::CITOYEN_CLASSES['actif'];
    }

    public static function countAgentClasses(): array
    {
        $counts = array_fill_keys(array_keys(self::AGENT_CLASSES), 0);
        User::where('role', 'AGENT')->get(['id'])->each(function ($agent) use (&$counts) {
            $class = self::classifierAgent($agent->id);
            $counts[$class['key']] = ($counts[$class['key']] ?? 0) + 1;
        });
        return $counts;
    }

    public static function countCitoyenClasses(): array
    {
        $counts = array_fill_keys(array_keys(self::CITOYEN_CLASSES), 0);
        User::where('role', 'CITOYEN')->get(['id'])->each(function ($citoyen) use (&$counts) {
            $class = self::classifierCitoyen($citoyen->id);
            $counts[$class['key']] = ($counts[$class['key']] ?? 0) + 1;
        });
        return $counts;
    }
}

<?php
namespace App\Models;

use App\Models\Signalement;
use App\Services\AgentStatsService;
use Illuminate\Database\Eloquent\Model;

class Rapport extends Model
{
    protected $fillable = [
        'dateGeneration', 'date_debut', 'date_fin',
        'nbrSignalement', 'nbr_en_attente', 'nbr_en_cours', 'nbr_termines', 'nbr_rejetes', 'nbr_critiques',
        'quantiteOrdure',
        'taux_resolution', 'taux_refus',
        'tempsMoyenneTraitement',
        'temps_moyen_traitement_h', 'temps_moyen_acceptation_h',
        'total_agents', 'agents_disponibles', 'agents_occupes', 'agents_absents', 'agents_inactifs',
        'taux_presence', 'zones_critiques', 'total_zones',
        'notes', 'user_id',
    ];

    protected $casts = [
        'dateGeneration' => 'date',
        'date_debut'     => 'date',
        'date_fin'       => 'date',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function calculerStats(?string $debut = null, ?string $fin = null): array
    {
        $query = fn() => Signalement::query()
            ->when($debut, fn($q) => $q->whereDate('created_at', '>=', $debut))
            ->when($fin,   fn($q) => $q->whereDate('created_at', '<=', $fin));

        $total     = $query()->count();
        $attente   = $query()->where('statut', 'enAttente')->count();
        $cours     = $query()->where('statut', 'enCours')->count();
        $termines  = $query()->where('statut', 'terminer')->count();
        $rejetes   = $query()->where('statut', 'rejeter')->count();
        $critiques = $query()->where('priorite', 'critique')->whereIn('statut', ['enAttente', 'enCours'])->count();

        $denom = max(1, $total);
        $tauxRes  = round(($termines / $denom) * 100, 2);
        $tauxRefus = round(($rejetes  / $denom) * 100, 2);

        $svc = app(AgentStatsService::class);
        $tempsMoyH = $svc->getTempsMoyenTraitement();
        $avgAcceptH = $svc->getTempsMoyenAcceptation();
        $agents = $svc->getAgentStats();
        $totalAgents = $agents['total'];
        $disponibles = $agents['disponibles'];
        $occupes = $agents['occupes'];
        $absents = $agents['absents'];
        $inactifs = $agents['inactifs'];
        $taux_presence = $totalAgents > 0 ? round((($totalAgents - $absents - $inactifs) / $totalAgents) * 100, 2) : 0;
        $zonesCritiques = $svc->getZonesCritiques();
        $totalZones = Zone::count();

        return [
            'nbrSignalement'           => $total,
            'nbr_en_attente'           => $attente,
            'nbr_en_cours'             => $cours,
            'nbr_termines'             => $termines,
            'nbr_rejetes'              => $rejetes,
            'nbr_critiques'            => $critiques,
            'taux_resolution'          => $tauxRes,
            'taux_refus'               => $tauxRefus,
            'temps_moyen_traitement_h' => round($tempsMoyH ?? 0, 2),
            'temps_moyen_acceptation_h'=> $avgAcceptH,
            'total_agents'             => $totalAgents,
            'agents_disponibles'       => $disponibles,
            'agents_occupes'           => $occupes,
            'agents_absents'           => $absents,
            'agents_inactifs'          => $inactifs,
            'taux_presence'            => $taux_presence,
            'zones_critiques'          => $zonesCritiques,
            'total_zones'              => $totalZones,
        ];
    }
}

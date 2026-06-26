<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Zone;
use App\Models\Categorie;
use App\Models\Signalement;
use App\Models\Attribution;
use App\Models\Evaluation;
use App\Models\SupportRequest;
use App\Models\Materiel;
use App\Models\SignalementPhoto;
use App\Models\NotificationPreference;
use App\Models\Localisation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ========== ZONES ==========
        $zones = [];
        $zoneData = [
            ['nomZone' => 'Dakar-Plateau', 'superficie' => 45.2, 'nombreHabitant' => 35000, 'latitude' => 14.6937, 'longitude' => -17.4441],
            ['nomZone' => 'Médina', 'superficie' => 22.5, 'nombreHabitant' => 85000, 'latitude' => 14.6789, 'longitude' => -17.4378],
            ['nomZone' => 'Gueule Tapée', 'superficie' => 12.8, 'nombreHabitant' => 42000, 'latitude' => 14.6652, 'longitude' => -17.4582],
            ['nomZone' => 'Fann-Point E', 'superficie' => 18.3, 'nombreHabitant' => 28000, 'latitude' => 14.6830, 'longitude' => -17.4700],
            ['nomZone' => 'Grand Yoff', 'superficie' => 30.1, 'nombreHabitant' => 120000, 'latitude' => 14.7300, 'longitude' => -17.4780],
            ['nomZone' => 'Pikine', 'superficie' => 55.0, 'nombreHabitant' => 250000, 'latitude' => 14.7500, 'longitude' => -17.3900],
            ['nomZone' => 'Guediawaye', 'superficie' => 42.7, 'nombreHabitant' => 180000, 'latitude' => 14.7800, 'longitude' => -17.4000],
            ['nomZone' => 'Rufisque', 'superficie' => 35.4, 'nombreHabitant' => 95000, 'latitude' => 14.7200, 'longitude' => -17.2800],
        ];
        foreach ($zoneData as $z) {
            $zones[] = Zone::updateOrCreate(['nomZone' => $z['nomZone']], $z);
        }

        // ========== CATÉGORIES ==========
        $categories = [];
        $catData = [
            ['nom' => 'Voirie', 'description' => 'Routes, trottoirs, nids-de-poule', 'priorite' => 'moyenne'],
            ['nom' => 'Éclairage public', 'description' => 'Lampadaires et éclairage', 'priorite' => 'moyenne'],
            ['nom' => 'Assainissement', 'description' => 'Égouts, caniveaux, eaux usées', 'priorite' => 'critique'],
            ['nom' => 'Propreté', 'description' => 'Ordures, dépotoirs sauvages', 'priorite' => 'faible'],
            ['nom' => 'Espace vert', 'description' => 'Parcs, jardins, arbres', 'priorite' => 'faible'],
            ['nom' => 'Bâtiment public', 'description' => 'Écoles, marchés, équipements', 'priorite' => 'moyenne'],
            ['nom' => 'Sécurité', 'description' => 'Éclairage défectueux, zones dangereuses', 'priorite' => 'critique'],
            ['nom' => 'Stationnement', 'description' => 'Stationnement illégal, parking', 'priorite' => 'faible'],
            ['nom' => 'Bruit', 'description' => 'Nuissances sonores', 'priorite' => 'moyenne'],
            ['nom' => 'Signalisation', 'description' => 'Panneaux, feux tricolores', 'priorite' => 'critique'],
        ];
        foreach ($catData as $c) {
            $categories[] = Categorie::firstOrCreate(['nom' => $c['nom']], $c);
        }

        // ========== USERS ==========
        $admin = User::firstOrCreate(['email' => 'admin@smc.sn'], [
            'name' => 'Admin', 'prenom' => 'System',
            'password' => Hash::make('password'),
            'role' => 'ADMIN', 'actif' => true,
            'telephone' => '77 000 00 00',
            'age' => 40, 'localite' => 'Dakar',
        ]);

        $agents = [];
        $agentData = [
            ['name' => 'Diallo', 'prenom' => 'Moussa', 'email' => 'agent1@smc.sn', 'zone' => 0, 'tel' => '77 111 11 11', 'age' => 32],
            ['name' => 'Sow', 'prenom' => 'Aminata', 'email' => 'agent2@smc.sn', 'zone' => 1, 'tel' => '77 222 22 22', 'age' => 28],
            ['name' => 'Faye', 'prenom' => 'Ousmane', 'email' => 'agent3@smc.sn', 'zone' => 2, 'tel' => '77 333 33 33', 'age' => 35],
            ['name' => 'Ba', 'prenom' => 'Fatou', 'email' => 'agent4@smc.sn', 'zone' => 3, 'tel' => '77 444 44 44', 'age' => 30],
            ['name' => 'Diop', 'prenom' => 'Mamadou', 'email' => 'agent5@smc.sn', 'zone' => 4, 'tel' => '77 555 55 55', 'age' => 27],
            ['name' => 'Niang', 'prenom' => 'Aissatou', 'email' => 'agent6@smc.sn', 'zone' => 0, 'tel' => '77 666 66 66', 'age' => 33],
        ];
        foreach ($agentData as $i => $a) {
            $agents[] = User::firstOrCreate(['email' => $a['email']], [
                'name' => $a['name'], 'prenom' => $a['prenom'],
                'password' => Hash::make('password'),
                'role' => 'AGENT', 'actif' => true,
                'disponible' => $i < 4,
                'pointer' => $i < 4,
                'heurePointage' => $i < 4 ? now()->subHours(rand(1, 4)) : null,
                'zone_id' => $zones[$a['zone']]->id,
                'telephone' => $a['tel'],
                'age' => $a['age'], 'localite' => 'Dakar',
            ]);
        }

        $citoyens = [];
        $citoyenData = [
            ['name' => 'Ndiaye', 'prenom' => 'Fatou', 'email' => 'citoyen1@smc.sn', 'zone' => 0, 'tel' => '78 111 11 11'],
            ['name' => 'Kane', 'prenom' => 'Ibrahima', 'email' => 'citoyen2@smc.sn', 'zone' => 1, 'tel' => '78 222 22 22'],
            ['name' => 'Sarr', 'prenom' => 'Marième', 'email' => 'citoyen3@smc.sn', 'zone' => 2, 'tel' => '78 333 33 33'],
            ['name' => 'Thiam', 'prenom' => 'Aliou', 'email' => 'citoyen4@smc.sn', 'zone' => 3, 'tel' => '78 444 44 44'],
            ['name' => 'Gueye', 'prenom' => 'Khadija', 'email' => 'citoyen5@smc.sn', 'zone' => 4, 'tel' => '78 555 55 55'],
            ['name' => 'Fall', 'prenom' => 'Boubacar', 'email' => 'citoyen6@smc.sn', 'zone' => 0, 'tel' => '78 666 66 66'],
            ['name' => 'Cissé', 'prenom' => 'Rokhaya', 'email' => 'citoyen7@smc.sn', 'zone' => 1, 'tel' => '78 777 77 77'],
            ['name' => 'Ndour', 'prenom' => 'Cheikh', 'email' => 'citoyen8@smc.sn', 'zone' => 2, 'tel' => '78 888 88 88'],
        ];
        foreach ($citoyenData as $c) {
            $citoyens[] = User::firstOrCreate(['email' => $c['email']], [
                'name' => $c['name'], 'prenom' => $c['prenom'],
                'password' => Hash::make('password'),
                'role' => 'CITOYEN', 'actif' => true,
                'zone_id' => $zones[$c['zone']]->id,
                'telephone' => $c['tel'],
                'age' => rand(20, 60), 'localite' => 'Dakar',
            ]);
        }

        // ========== NOTIFICATION PREFERENCES ==========
        foreach ([$admin, ...$agents, ...$citoyens] as $user) {
            NotificationPreference::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'notification_systeme' => true,
                    'notification_email' => $user->role !== 'CITOYEN',
                    'notification_push' => $user->role === 'AGENT',
                ]
            );
        }

        // ========== LOCALISATIONS ==========
        $dakarCoords = [
            [14.6937, -17.4441], [14.6789, -17.4378], [14.6652, -17.4582],
            [14.7210, -17.4680], [14.7500, -17.4700], [14.7300, -17.4500],
        ];
        foreach ($agents as $i => $agent) {
            if ($i < 4) {
                Localisation::create([
                    'user_id' => $agent->id,
                    'latitude' => $dakarCoords[$i % count($dakarCoords)][0] + (rand(-10, 10) / 1000),
                    'longitude' => $dakarCoords[$i % count($dakarCoords)][1] + (rand(-10, 10) / 1000),
                    'dateHeure' => now()->subMinutes(rand(5, 60)),
                ]);
            }
        }

        // ========== SIGNALEMENTS ==========
        $signalements = [];
        $situations = [
            // [citoyen_index, zone_index, categorie_index, statut, priorite, days_ago, description]
            [0, 0, 0, 'terminer', 'critique', 10, 'Nid-de-poule dangereux sur l\'avenue Lamine Guèye'],
            [1, 1, 1, 'enCours', 'moyenne', 3, 'Lampadaire défectueux rue 10 Médina'],
            [2, 2, 2, 'enAttente', 'critique', 1, 'Caniveau bouché à Gueule Tapée, risque d\'inondation'],
            [3, 3, 3, 'terminer', 'faible', 15, 'Dépotoir sauvage à Fann Résidence'],
            [4, 4, 4, 'rejeter', 'faible', 20, 'Demande d\'élagage d\'arbre non justifiée'],
            [0, 0, 5, 'enAttente', 'moyenne', 0, 'Porte d\'entrée de l\'école endommagée'],
            [1, 1, 6, 'enCours', 'critique', 2, 'Zone non éclairée propice aux agressions'],
            [2, 2, 7, 'enAttente', 'faible', 4, 'Voiture garée sur le trottoir depuis 3 jours'],
            [3, 3, 8, 'terminer', 'moyenne', 8, 'Bruit constant du chantier voisin après 22h'],
            [4, 4, 9, 'enCours', 'critique', 5, 'Feu tricolore en panne au carrefour'],
            [5, 0, 0, 'enAttente', 'moyenne', 0, 'Route dégradée devant le marché'],
            [6, 1, 2, 'enAttente', 'critique', 1, 'Égout à ciel ouvert, risque sanitaire'],
            [7, 2, 3, 'terminer', 'faible', 12, 'Encombrants non collectés'],
            [0, 0, 1, 'enCours', 'moyenne', 6, 'Éclairage public insuffisant'],
            [1, 1, 4, 'enAttente', 'faible', 0, 'Branche d\'arbre menaçant de tomber'],
        ];
        foreach ($situations as $i => $s) {
            $cit = $citoyens[$s[0]];
            $zone = $zones[$s[1]];
            $cat = $categories[$s[2]];
            $sig = Signalement::create([
                'position' => $cit->localite . ', zone ' . $zone->nomZone,
                'latitude' => $dakarCoords[$s[1] % count($dakarCoords)][0] + (rand(-20, 20) / 1000),
                'longitude' => $dakarCoords[$s[1] % count($dakarCoords)][1] + (rand(-20, 20) / 1000),
                'description' => $s[6],
                'statut' => $s[3],
                'priorite' => $s[4],
                'dateSignalement' => now()->subDays($s[5]),
                'date_resolution' => in_array($s[3], ['terminer']) ? now()->subDays(max(0, $s[5] - 3)) : null,
                'commentaire_agent' => $s[3] === 'terminer' ? 'Intervention terminée avec succès.' : null,
                'commentaire_admin' => $s[3] === 'rejeter' ? 'Demande non conforme à nos critères.' : null,
                'user_id' => $cit->id,
                'zone_id' => $zone->id,
                'categorie_id' => $cat->id,
            ]);
            $signalements[] = $sig;

            // Add photos for some signalements
            if ($i % 3 === 0) {
                SignalementPhoto::create([
                    'signalement_id' => $sig->id,
                    'path' => 'signalements/example-' . ($i % 4 + 1) . '.jpg',
                    'type' => 'citoyen',
                    'description' => 'Photo du signalement',
                ]);
            }
        }

        // ========== ATTRIBUTIONS ==========
        $attributionCount = 0;
        foreach ($signalements as $i => $sig) {
            if (in_array($sig->statut, ['enCours', 'terminer'])) {
                $agent = $agents[$i % count($agents)];
                Attribution::create([
                    'signalement_id' => $sig->id,
                    'agent_id' => $agent->id,
                    'admin_id' => $admin->id,
                    'dateHeureAttribution' => $sig->created_at->addHours(rand(1, 24)),
                ]);
                $attributionCount++;
            }
        }

        // ========== ÉVALUATIONS ==========
        foreach ($signalements as $sig) {
            if ($sig->statut === 'terminer') {
                $cit = collect($citoyens)->firstWhere('id', $sig->user_id) ?? $citoyens[0];
                Evaluation::create([
                    'signalement_id' => $sig->id,
                    'user_id' => $cit->id,
                    'note' => rand(3, 5),
                    'commentaire' => collect([
                        'Très satisfait du service rendu.',
                        'Merci pour la rapidité d\'intervention.',
                        'Problème résolu efficacement.',
                        'Bon travail, mais pourrait être plus rapide.',
                        'Excellent service, je recommande.',
                    ])->random(),
                    'probleme_resolu' => true,
                ]);
            }
        }

        // ========== SUPPORT REQUESTS (agent requests) ==========
        $supportTypes = ['renfort', 'materiel', 'panne_vehicule', 'assistance_urgente'];
        $supportStatuses = ['en_attente', 'valide', 'refusé'];
        foreach ($agents as $i => $agent) {
            if ($i % 2 === 0) {
                SupportRequest::create([
                    'agent_id' => $agent->id,
                    'type' => $supportTypes[$i % count($supportTypes)],
                    'description' => collect([
                        'Besoin de renfort pour une intervention majeure à Médina.',
                        'Requête de matériel supplémentaire pour le nettoyage.',
                        'Véhicule en panne, besoin d\'un remplacement.',
                        'Assistance urgente requise pour une situation dangereuse.',
                    ])->random(),
                    'statut' => $supportStatuses[$i % count($supportStatuses)],
                    'traite_par' => $i % 3 === 0 ? $admin->id : null,
                    'date_traitement' => $i % 3 === 0 ? now()->subHours(rand(2, 48)) : null,
                ]);
            }
        }

        // ========== MATÉRIELS ==========
        $materielData = [
            ['nom' => 'Camion benne A-001', 'description' => 'Camion benne pour collecte d\'ordures', 'categorie' => 'vehicule', 'statut' => 'disponible'],
            ['nom' => 'Camion benne A-002', 'description' => 'Camion benne pour collecte d\'ordures', 'categorie' => 'vehicule', 'statut' => 'attribue', 'agent_index' => 0],
            ['nom' => 'Pelle mécanique P-01', 'description' => 'Pelle mécanique pour travaux', 'categorie' => 'equipement', 'statut' => 'disponible'],
            ['nom' => 'Nettoyeuse haute pression', 'description' => 'Nettoyeuse pour caniveaux', 'categorie' => 'equipement', 'statut' => 'attribue', 'agent_index' => 1],
            ['nom' => 'Moto 4x4 M-001', 'description' => 'Moto tout-terrain pour patrouille', 'categorie' => 'vehicule', 'statut' => 'en_maintenance'],
            ['nom' => 'Ordinateur portable', 'description' => 'PC portable pour rapports', 'categorie' => 'informatique', 'statut' => 'disponible'],
            ['nom' => 'Kit signalisation', 'description' => 'Cônes et panneaux de chantier', 'categorie' => 'equipement', 'statut' => 'attribue', 'agent_index' => 2],
            ['nom' => 'Camionnette utilitaire', 'description' => 'Camionnette pour interventions légères', 'categorie' => 'vehicule', 'statut' => 'hors_service'],
            ['nom' => 'Groupe électrogène', 'description' => 'Groupe électrogène de chantier', 'categorie' => 'equipement', 'statut' => 'disponible'],
            ['nom' => 'Tronçonneuse', 'description' => 'Tronçonneuse pour élagage', 'categorie' => 'equipement', 'statut' => 'attribue', 'agent_index' => 3],
            ['nom' => 'VTT électrique V-01', 'description' => 'Vélo électrique pour déplacements', 'categorie' => 'vehicule', 'statut' => 'disponible'],
            ['nom' => 'Tablette GPS', 'description' => 'Tablette avec GPS pour navigation', 'categorie' => 'informatique', 'statut' => 'attribue', 'agent_index' => 0],
        ];
        foreach ($materielData as $m) {
            $agentId = null;
            $dateAttribution = null;
            if (isset($m['agent_index'])) {
                $agentId = $agents[$m['agent_index']]->id;
                $dateAttribution = now()->subDays(rand(5, 30));
            }
            Materiel::create([
                'nom' => $m['nom'],
                'description' => $m['description'],
                'categorie' => $m['categorie'],
                'statut' => $m['statut'],
                'agent_id' => $agentId,
                'date_attribution' => $dateAttribution,
            ]);
        }
    }
}

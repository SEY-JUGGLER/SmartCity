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
use App\Models\ActivityLog;
use App\Models\Commune;
use App\Notifications\SignalementNotification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ========== COMMUNES (19 communes de Dakar) ==========
        $communes = [];
        $communeData = [
            ['nom' => 'Dakar-Plateau', 'description' => 'Centre administratif et économique de Dakar'],
            ['nom' => 'Dakar-Bel-Air', 'description' => 'Commune résidentielle du centre de Dakar'],
            ['nom' => 'Dakar-Grand-Yoff', 'description' => 'Grande commune populaire du nord de Dakar'],
            ['nom' => 'Dakar-Médina', 'description' => 'Commune historique et dense de Dakar'],
            ['nom' => 'Dakar-Fann-Point-E-Amitié', 'description' => 'Commune résidentielle et universitaire'],
            ['nom' => 'Dakar-Sacré-Cœur', 'description' => 'Commune commerciale du centre de Dakar'],
            ['nom' => 'Dakar-Khoury-Gueye', 'description' => 'Commune du centre-ville de Dakar'],
            ['nom' => 'Dakar-Dieuppeul-Derklé', 'description' => 'Commune résidentielle de l\'est de Dakar'],
            ['nom' => 'Dakar-Point E', 'description' => 'Commune résidentielle huppée de Dakar'],
            ['nom' => 'Dakar-Sénégal', 'description' => 'Commune du centre de Dakar'],
            ['nom' => 'Dakar-Gorée', 'description' => 'Commune historique et touristique (île de Gorée)'],
            ['nom' => 'Dakar-Patte d\'Oie', 'description' => 'Commune du nord de Dakar'],
            ['nom' => 'Dakar-Guédiawaye', 'description' => 'Commune de la banlieue nord de Dakar'],
            ['nom' => 'Dakar-Pikine', 'description' => 'Commune de la banlieue est de Dakar'],
            ['nom' => 'Dakar-Rufisque', 'description' => 'Commune historique à l\'est de Dakar'],
            ['nom' => 'Dakar-Malika', 'description' => 'Commune de la banlieue nord de Dakar'],
            ['nom' => 'Dakar-Sangalkam', 'description' => 'Commune périurbaine de l\'est de Dakar'],
            ['nom' => 'Dakar-Sebikotane', 'description' => 'Commune périurbaine de l\'est de Dakar'],
            ['nom' => 'Dakar-Sendou', 'description' => 'Commune de la banlieue nord de Dakar'],
        ];
        foreach ($communeData as $cd) {
            $communes[] = Commune::firstOrCreate(['nom' => $cd['nom']], $cd);
        }

        // ========== ZONES ==========
        $zones = [];
        $zoneData = [
            // [nomZone, superficie, habitants, lat, lng, commune_index]
            ['Dakar-Plateau', 45.2, 35000, 14.6937, -17.4441, 0],
            ['Médina', 22.5, 85000, 14.6789, -17.4378, 3],
            ['Gueule Tapée', 12.8, 42000, 14.6652, -17.4582, 0],
            ['Fann-Point E', 18.3, 28000, 14.6830, -17.4700, 4],
            ['Grand Yoff', 30.1, 120000, 14.7300, -17.4780, 2],
            ['Pikine', 55.0, 250000, 14.7500, -17.3900, 13],
            ['Guediawaye', 42.7, 180000, 14.7800, -17.4000, 12],
            ['Rufisque Ouest', 35.4, 95000, 14.7200, -17.2800, 14],
            ['Thiaroye', 28.6, 130000, 14.7400, -17.3700, 13],
            ['Yeumbeul', 18.9, 95000, 14.7600, -17.3600, 13],
        ];
        foreach ($zoneData as $z) {
            $zones[] = Zone::updateOrCreate(['nomZone' => $z[0]], [
                'nomZone' => $z[0],
                'superficie' => $z[1],
                'nombreHabitant' => $z[2],
                'latitude' => $z[3],
                'longitude' => $z[4],
                'commune_id' => $communes[$z[5]]->id,
            ]);
        }

        // ========== CATÉGORIES ==========
        $categories = [];
        $catData = [
            ['nom' => 'Biodéchets', 'description' => 'Restes alimentaires, déchets verts', 'priorite' => 'moyenne'],
            ['nom' => 'Emballages recyclables', 'description' => 'Plastique, carton, métal, verre', 'priorite' => 'faible'],
            ['nom' => 'Textiles', 'description' => 'Vêtements, chaussures, linge', 'priorite' => 'faible'],
            ['nom' => 'Déchets dangereux', 'description' => 'Piles, solvants, peintures, médicaments', 'priorite' => 'critique'],
            ['nom' => 'Équipements électriques et électroniques (DEEE)', 'description' => 'Appareils électroniques et électriques hors d\'usage', 'priorite' => 'moyenne'],
            ['nom' => 'Encombrants', 'description' => 'Meubles, gros objets', 'priorite' => 'faible'],
            ['nom' => 'Ordures ménagères résiduelles', 'description' => 'Déchets non recyclables', 'priorite' => 'moyenne'],
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
            [0, 0, 0, 'terminer', 'moyenne', 10, 'Restes alimentaires non collectés depuis une semaine'],
            [1, 1, 1, 'enCours', 'faible', 3, 'Bac de recyclage plein rue 10 Médina'],
            [2, 2, 2, 'enAttente', 'faible', 1, 'Dépôt sauvage de vêtements usagés à Gueule Tapée'],
            [3, 3, 3, 'terminer', 'critique', 15, 'Piles et batteries abandonnées près du marché Fann'],
            [4, 4, 4, 'rejeter', 'moyenne', 20, 'Ancien réfrigérateur déposé sur la voie publique (déjà signalé)'],
            [0, 0, 5, 'enAttente', 'faible', 0, 'Canapé et matelas abandonnés sur le trottoir'],
            [1, 1, 6, 'enCours', 'moyenne', 2, 'Bac à ordures débordant au carrefour Médina'],
            [2, 2, 0, 'enAttente', 'moyenne', 4, 'Déchets verts obstruant le caniveau'],
            [3, 3, 4, 'terminer', 'moyenne', 8, 'Machine à laver hors d\'usage abandonnée'],
            [4, 4, 3, 'enCours', 'critique', 5, 'Fûts de peinture chimique déversés dans la rue'],
            [5, 0, 1, 'enAttente', 'faible', 0, 'Cartons et plastiques éparpillés devant le marché'],
            [6, 1, 0, 'enAttente', 'critique', 1, 'Déchets alimentaires en décomposition, risque sanitaire'],
            [7, 2, 5, 'terminer', 'faible', 12, 'Encombrants non collectés après avis'],
            [0, 0, 6, 'enCours', 'moyenne', 6, 'Ordures ménagères non ramassées depuis 3 jours'],
            [1, 1, 4, 'enAttente', 'moyenne', 0, 'Téléviseur cassé déposé à côté du conteneur'],
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

        // ========== 5 SIGNALEMENTS SUPPLÉMENTAIRES ==========
        $moreSituations = [
            [5, 4, 0, 'enAttente', 'moyenne', 2, 'Biodéchets en fermentation devant le restaurant, odeur nauséabonde'],
            [6, 5, 1, 'terminer', 'faible', 7, 'Bac de tri sélectif endommagé à Pikine'],
            [7, 6, 6, 'enCours', 'moyenne', 1, 'Ordures ménagères déversées sur la voie publique à Guediawaye'],
            [5, 7, 3, 'enAttente', 'critique', 0, 'Produits chimiques abandonnés près de l\'école à Rufisque'],
            [6, 0, 5, 'terminer', 'faible', 14, 'Mobilier de bureau encombrant déposé sur le trottoir'],
        ];
        foreach ($moreSituations as $i => $s) {
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
                'commentaire_admin' => null,
                'user_id' => $cit->id,
                'zone_id' => $zone->id,
                'categorie_id' => $cat->id,
            ]);
            $signalements[] = $sig;
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
        $noteLabels = ['satisfaisant', 'très satisfaisant', 'excellent'];
        foreach ($signalements as $sig) {
            if ($sig->statut === 'terminer') {
                $cit = collect($citoyens)->firstWhere('id', $sig->user_id) ?? $citoyens[0];
                Evaluation::create([
                    'signalement_id' => $sig->id,
                    'user_id' => $cit->id,
                    'note' => $noteLabels[array_rand($noteLabels)],
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
        $supportDescs = [
            'Besoin de renfort pour une intervention majeure à Médina.',
            'Requête de matériel supplémentaire pour le nettoyage.',
            'Véhicule en panne, besoin d\'un remplacement.',
            'Assistance urgente requise pour une situation dangereuse.',
        ];
        foreach ($agents as $i => $agent) {
            SupportRequest::create([
                'agent_id' => $agent->id,
                'type' => $supportTypes[$i % count($supportTypes)],
                'description' => $supportDescs[$i % count($supportDescs)],
                'statut' => $supportStatuses[$i % count($supportStatuses)],
                'traite_par' => in_array($i, [0, 3]) ? $admin->id : null,
                'date_traitement' => in_array($i, [0, 3]) ? now()->subHours(rand(2, 48)) : null,
            ]);
            // Second request for some agents
            if (in_array($i, [1, 4])) {
                SupportRequest::create([
                    'agent_id' => $agent->id,
                    'type' => $supportTypes[($i + 2) % count($supportTypes)],
                    'description' => $supportDescs[($i + 1) % count($supportDescs)],
                    'statut' => 'en_attente',
                    'traite_par' => null,
                    'date_traitement' => null,
                ]);
            }
        }

        // ========== NOTIFICATIONS PRÉ-ENREGISTRÉES ==========
        $notifications = [];
        $uuid = fn () => sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff), mt_rand(0, 0xffff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
        $now = now();

        // Notifications pour l'admin
        $adminNotifData = [
            ['Nouveau signalement #1 reçu', 'Un citoyen a signalé un dépôt sauvage à Dakar-Plateau', 'signalement_create', 1],
            ['Nouveau signalement #8 reçu', 'Un citoyen a signalé des déchets verts obstruant un caniveau', 'signalement_create', 8],
            ['Nouveau signalement #16 reçu', 'Biodéchets en fermentation devant un restaurant', 'signalement_create', 16],
            ['Nouveau signalement #18 reçu', 'Ordures ménagères déversées sur la voie publique à Guediawaye', 'signalement_create', 18],
            ['Agent Moussa Diallo non disponible', 'L\'agent Moussa Diallo a signalé son indisponibilité', 'info', null],
        ];
        foreach ($adminNotifData as $nd) {
            DB::table('notifications')->insert([
                'id' => $uuid(),
                'type' => 'App\Notifications\SignalementNotification',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $admin->id,
                'data' => json_encode(['title' => $nd[0], 'body' => $nd[1], 'type' => $nd[2], 'signalement_id' => $nd[3]]),
                'read_at' => rand(0, 1) ? $now->subHours(rand(1, 24)) : null,
                'created_at' => $now->subHours(rand(1, 48)),
                'updated_at' => $now->subHours(rand(1, 48)),
            ]);
        }

        // Notifications pour les agents
        $agentNotifTemplates = [
            ['Mission #1 assignée', 'Nouveau signalement attribué : Restes alimentaires non collectés', 'signalement_attributed', 1],
            ['Mission #7 assignée', 'Nouveau signalement attribué : Bac à ordures débordant', 'signalement_attributed', 7],
            ['Mission #14 assignée', 'Nouveau signalement attribué : Ordures ménagères non ramassées', 'signalement_attributed', 14],
            ['Mission #18 assignée', 'Nouveau signalement attribué à Guediawaye', 'signalement_attributed', 18],
        ];
        foreach ($agents as $i => $agent) {
            $tpl = $agentNotifTemplates[$i % count($agentNotifTemplates)];
            DB::table('notifications')->insert([
                'id' => $uuid(),
                'type' => 'App\Notifications\SignalementNotification',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $agent->id,
                'data' => json_encode(['title' => $tpl[0], 'body' => $tpl[1] . ' (zone ' . $zones[$agentData[$i]['zone']]->nomZone . ')', 'type' => $tpl[2], 'signalement_id' => $tpl[3]]),
                'read_at' => rand(0, 1) ? $now->subHours(rand(1, 12)) : null,
                'created_at' => $now->subHours(rand(1, 36)),
                'updated_at' => $now->subHours(rand(1, 36)),
            ]);
            // Each agent gets one unread notification about a recent assignment
            if ($i < 3) {
                $recentSig = $signalements[array_rand($signalements)];
                DB::table('notifications')->insert([
                    'id' => $uuid(),
                    'type' => 'App\Notifications\SignalementNotification',
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id' => $agent->id,
                    'data' => json_encode([
                        'title' => 'Nouvelle mission #' . $recentSig->id,
                        'body' => 'Signalement urgent : ' . $recentSig->description,
                        'type' => 'signalement_attributed',
                        'signalement_id' => $recentSig->id,
                    ]),
                    'read_at' => null,
                    'created_at' => $now->subMinutes(rand(5, 120)),
                    'updated_at' => $now->subMinutes(rand(5, 120)),
                ]);
            }
        }

        // Notifications pour les citoyens
        $citoyenNotifTemplates = [
            ['Signalement #1 résolu', 'Votre signalement à Dakar-Plateau a été traité avec succès.', 'signalement_status', 1],
            ['Signalement #4 résolu', 'Votre signalement de déchets dangereux à Fann-Point E a été traité.', 'signalement_status', 4],
            ['Signalement #17 résolu', 'Votre signalement à Pikine a été résolu.', 'signalement_status', 17],
            ['Signalement #20 résolu', 'Votre signalement d\'encombrants a été traité.', 'signalement_status', 20],
            ['Signalement #5 refusé', 'Votre signalement de DEEE a été refusé (déjà signalé).', 'signalement_status', 5],
        ];
        foreach ($citoyens as $i => $citoyen) {
            $tpl = $citoyenNotifTemplates[$i % count($citoyenNotifTemplates)];
            DB::table('notifications')->insert([
                'id' => $uuid(),
                'type' => 'App\Notifications\SignalementNotification',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $citoyen->id,
                'data' => json_encode(['title' => $tpl[0], 'body' => $tpl[1], 'type' => $tpl[2], 'signalement_id' => $tpl[3]]),
                'read_at' => $i < 3 ? $now->subHours(rand(1, 24)) : null,
                'created_at' => $now->subHours(rand(1, 72)),
                'updated_at' => $now->subHours(rand(1, 72)),
            ]);
        }

        // ========== ACTIVITY LOGS ==========
        $actions = ['signalement_created', 'signalement_attributed', 'signalement_status_changed', 'support_validated', 'rapport_generated'];
        foreach ($signalements as $i => $sig) {
            if ($i % 4 === 0) continue;
            ActivityLog::create([
                'user_id' => $i % 3 === 0 ? $admin->id : $citoyens[$i % count($citoyens)]->id,
                'action' => $actions[$i % count($actions)],
                'target_type' => 'signalement',
                'target_id' => $sig->id,
                'description' => match ($i % 4) {
                    1 => 'Signalement #' . $sig->id . ' attribué à un agent',
                    2 => 'Statut du signalement #' . $sig->id . ' mis à jour : ' . $sig->statut,
                    3 => 'Nouveau signalement #' . $sig->id . ' créé par ' . ($sig->citoyen?->prenom ?? 'citoyen'),
                    default => 'Action sur le signalement #' . $sig->id,
                },
                'created_at' => $sig->created_at,
                'updated_at' => $sig->created_at,
            ]);
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

        // ========== MATÉRIELS SUPPLÉMENTAIRES ==========
        $moreMateriel = [
            ['nom' => 'Broyeur à déchets B-01', 'description' => 'Broyeur pour déchets verts', 'categorie' => 'equipement', 'statut' => 'disponible'],
            ['nom' => 'Camion benne A-003', 'description' => 'Camion benne récent pour collecte', 'categorie' => 'vehicule', 'statut' => 'disponible'],
            ['nom' => 'Balayeuse mécanique BM-01', 'description' => 'Balayeuse pour voirie', 'categorie' => 'vehicule', 'statut' => 'attribue', 'agent_index' => 4],
            ['nom' => 'Conteneur 1000L C-01', 'description' => 'Grand conteneur pour déchets encombrants', 'categorie' => 'equipement', 'statut' => 'disponible'],
            ['nom' => 'Écran tactile kiosque', 'description' => 'Écran pour point d\'information citoyen', 'categorie' => 'informatique', 'statut' => 'hors_service'],
            ['nom' => 'Drone inspection D-01', 'description' => 'Drone pour inspection des décharges sauvages', 'categorie' => 'equipement', 'statut' => 'en_maintenance'],
            ['nom' => 'Caméra surveillance CS-01', 'description' => 'Caméra pour surveiller les dépôts sauvages', 'categorie' => 'equipement', 'statut' => 'disponible'],
            ['nom' => 'Véhicule électrique VE-01', 'description' => 'Quad électrique pour patrouille piétonne', 'categorie' => 'vehicule', 'statut' => 'disponible'],
            ['nom' => 'Aspirateur industriel', 'description' => 'Aspirateur pour déchets fins', 'categorie' => 'equipement', 'statut' => 'attribue', 'agent_index' => 5],
            ['nom' => 'Station météo SM-01', 'description' => 'Station pour suivi environnemental', 'categorie' => 'informatique', 'statut' => 'disponible'],
        ];
        foreach ($moreMateriel as $m) {
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rapports', function (Blueprint $table) {
            $table->date('date_debut')->nullable()->after('dateGeneration');
            $table->date('date_fin')->nullable()->after('date_debut');

            // Signalements breakdown
            $table->integer('nbr_en_attente')->default(0)->after('nbrSignalement');
            $table->integer('nbr_en_cours')->default(0)->after('nbr_en_attente');
            $table->integer('nbr_termines')->default(0)->after('nbr_en_cours');
            $table->integer('nbr_rejetes')->default(0)->after('nbr_termines');
            $table->integer('nbr_critiques')->default(0)->after('nbr_rejetes');

            // Performance
            $table->decimal('taux_resolution', 5, 2)->default(0)->after('quantiteOrdure');
            $table->decimal('taux_refus', 5, 2)->default(0)->after('taux_resolution');
            $table->decimal('temps_moyen_traitement_h', 8, 2)->nullable()->after('taux_refus');
            $table->decimal('temps_moyen_acceptation_h', 8, 2)->nullable()->after('temps_moyen_traitement_h');

            // Agents
            $table->integer('total_agents')->default(0)->after('temps_moyen_acceptation_h');
            $table->integer('agents_disponibles')->default(0)->after('total_agents');
            $table->integer('agents_occupes')->default(0)->after('agents_disponibles');
            $table->integer('agents_absents')->default(0)->after('agents_occupes');
            $table->integer('agents_inactifs')->default(0)->after('agents_absents');
            $table->decimal('taux_presence', 5, 2)->default(0)->after('agents_inactifs');

            // Zones
            $table->integer('zones_critiques')->default(0)->after('taux_presence');
            $table->integer('total_zones')->default(0)->after('zones_critiques');

            // Notes libres
            $table->text('notes')->nullable()->after('total_zones');
        });
    }

    public function down(): void
    {
        Schema::table('rapports', function (Blueprint $table) {
            $table->dropColumn([
                'date_debut', 'date_fin',
                'nbr_en_attente', 'nbr_en_cours', 'nbr_termines', 'nbr_rejetes', 'nbr_critiques',
                'taux_resolution', 'taux_refus',
                'temps_moyen_traitement_h', 'temps_moyen_acceptation_h',
                'total_agents', 'agents_disponibles', 'agents_occupes', 'agents_absents', 'agents_inactifs',
                'taux_presence', 'zones_critiques', 'total_zones', 'notes',
            ]);
        });
    }
};

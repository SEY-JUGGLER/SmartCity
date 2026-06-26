<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport — {{ optional($rapport->dateGeneration)->format('d/m/Y') }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 13px;
            color: #1a202c;
            background: #fff;
            padding: 32px 40px;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid #3b82f6;
            padding-bottom: 20px;
            margin-bottom: 28px;
        }
        .header-brand { font-size: 26px; font-weight: 800; color: #1d4ed8; letter-spacing: -0.5px; }
        .header-sub { font-size: 12px; color: #6b7280; margin-top: 4px; }
        .header-meta { text-align: right; font-size: 12px; color: #6b7280; }
        .header-meta strong { display: block; font-size: 14px; color: #1a202c; }

        /* Section */
        .section { margin-bottom: 24px; }
        .section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #3b82f6;
            border-left: 3px solid #3b82f6;
            padding-left: 8px;
            margin-bottom: 12px;
        }

        /* Grid */
        .grid { display: grid; gap: 10px; }
        .grid-2 { grid-template-columns: repeat(2, 1fr); }
        .grid-3 { grid-template-columns: repeat(3, 1fr); }
        .grid-4 { grid-template-columns: repeat(4, 1fr); }
        .grid-5 { grid-template-columns: repeat(5, 1fr); }
        .grid-6 { grid-template-columns: repeat(6, 1fr); }

        /* Stat card */
        .stat {
            border-radius: 8px;
            padding: 12px 14px;
            border: 1px solid #e5e7eb;
        }
        .stat-label { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #6b7280; margin-bottom: 4px; }
        .stat-value { font-size: 22px; font-weight: 800; color: #1a202c; line-height: 1; }
        .stat-suffix { font-size: 13px; font-weight: 600; }
        .stat-desc { font-size: 10px; color: #9ca3af; margin-top: 2px; }

        /* Colors */
        .c-blue   { background: #eff6ff; border-color: #bfdbfe; }
        .c-green  { background: #f0fdf4; border-color: #bbf7d0; }
        .c-amber  { background: #fffbeb; border-color: #fde68a; }
        .c-red    { background: #fef2f2; border-color: #fecaca; }
        .c-gray   { background: #f9fafb; border-color: #e5e7eb; }
        .c-cyan   { background: #ecfeff; border-color: #a5f3fc; }
        .c-purple { background: #f5f3ff; border-color: #ddd6fe; }

        .v-blue   { color: #1d4ed8; }
        .v-green  { color: #15803d; }
        .v-amber  { color: #b45309; }
        .v-red    { color: #dc2626; }
        .v-gray   { color: #374151; }
        .v-cyan   { color: #0e7490; }

        /* Notes box */
        .notes-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 14px;
            font-size: 12px;
            color: #374151;
            min-height: 60px;
            white-space: pre-wrap;
        }

        /* Divider */
        .divider { border: none; border-top: 1px solid #e5e7eb; margin: 20px 0; }

        /* Footer */
        .footer {
            margin-top: 32px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #9ca3af;
        }

        /* Print */
        @media print {
            body { padding: 16px 24px; }
            .no-print { display: none !important; }
            .stat { break-inside: avoid; }
        }
    </style>
</head>
<body>

    <!-- Print Button (hidden on print) -->
    <div class="no-print" style="text-align:right; margin-bottom:20px;">
        <button onclick="window.print()"
            style="background:#3b82f6;color:#fff;border:none;padding:8px 20px;border-radius:6px;font-size:13px;cursor:pointer;font-weight:600;">
            🖨 Imprimer / Enregistrer en PDF
        </button>
    </div>

    <!-- Header -->
    <div class="header">
        <div>
            <img src="{{ public_path('images/logo.png') }}" alt="SmartCity" style="height: 32px; margin-bottom: 6px;">
            <div class="header-brand">SmartCity</div>
            <div class="header-sub">Rapport d'activité — Ville intelligente</div>
        </div>
        <div class="header-meta">
            <strong>Rapport du {{ optional($rapport->dateGeneration)->format('d/m/Y') }}</strong>
            @if($rapport->date_debut && $rapport->date_fin)
                Période : {{ $rapport->date_debut->format('d/m/Y') }} → {{ $rapport->date_fin->format('d/m/Y') }}
            @endif
            <br>
            Généré par : {{ trim(($rapport->admin?->prenom ?? '') . ' ' . ($rapport->admin?->name ?? '')) ?: 'Administrateur' }}
            <br>
            Imprimé le : {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

    <!-- Signalements -->
    <div class="section">
        <div class="section-title">📋 Signalements</div>
        <div class="grid grid-6">
            <div class="stat c-blue">
                <div class="stat-label">Total</div>
                <div class="stat-value v-blue">{{ $rapport->nbrSignalement }}</div>
            </div>
            <div class="stat c-amber">
                <div class="stat-label">En attente</div>
                <div class="stat-value v-amber">{{ $rapport->nbr_en_attente }}</div>
            </div>
            <div class="stat c-cyan">
                <div class="stat-label">En cours</div>
                <div class="stat-value v-cyan">{{ $rapport->nbr_en_cours }}</div>
            </div>
            <div class="stat c-green">
                <div class="stat-label">Terminés</div>
                <div class="stat-value v-green">{{ $rapport->nbr_termines }}</div>
            </div>
            <div class="stat c-red">
                <div class="stat-label">Rejetés</div>
                <div class="stat-value v-red">{{ $rapport->nbr_rejetes }}</div>
            </div>
            <div class="stat {{ $rapport->nbr_critiques > 0 ? 'c-red' : 'c-green' }}">
                <div class="stat-label">Critiques actifs</div>
                <div class="stat-value {{ $rapport->nbr_critiques > 0 ? 'v-red' : 'v-green' }}">{{ $rapport->nbr_critiques }}</div>
            </div>
        </div>
    </div>

    <!-- Performance -->
    <div class="section">
        <div class="section-title">⚡ Performance</div>
        <div class="grid grid-5">
            <div class="stat {{ $rapport->taux_resolution >= 70 ? 'c-green' : ($rapport->taux_resolution >= 40 ? 'c-amber' : 'c-red') }}">
                <div class="stat-label">Taux résolution</div>
                <div class="stat-value {{ $rapport->taux_resolution >= 70 ? 'v-green' : ($rapport->taux_resolution >= 40 ? 'v-amber' : 'v-red') }}">
                    {{ $rapport->taux_resolution }}<span class="stat-suffix">%</span>
                </div>
            </div>
            <div class="stat {{ $rapport->taux_refus <= 10 ? 'c-green' : ($rapport->taux_refus <= 25 ? 'c-amber' : 'c-red') }}">
                <div class="stat-label">Taux de refus</div>
                <div class="stat-value {{ $rapport->taux_refus <= 10 ? 'v-green' : ($rapport->taux_refus <= 25 ? 'v-amber' : 'v-red') }}">
                    {{ $rapport->taux_refus }}<span class="stat-suffix">%</span>
                </div>
            </div>
            <div class="stat {{ ($rapport->temps_moyen_traitement_h ?? 0) <= 24 ? 'c-green' : 'c-amber' }}">
                <div class="stat-label">Tps moyen traitement</div>
                <div class="stat-value {{ ($rapport->temps_moyen_traitement_h ?? 0) <= 24 ? 'v-green' : 'v-amber' }}">
                    {{ $rapport->temps_moyen_traitement_h ?? '—' }}<span class="stat-suffix">{{ $rapport->temps_moyen_traitement_h ? 'h' : '' }}</span>
                </div>
            </div>
            <div class="stat {{ ($rapport->temps_moyen_acceptation_h ?? 0) <= 1 ? 'c-green' : (($rapport->temps_moyen_acceptation_h ?? 0) <= 4 ? 'c-amber' : 'c-red') }}">
                <div class="stat-label">Tps moyen acceptation</div>
                <div class="stat-value {{ ($rapport->temps_moyen_acceptation_h ?? 0) <= 1 ? 'v-green' : (($rapport->temps_moyen_acceptation_h ?? 0) <= 4 ? 'v-amber' : 'v-red') }}">
                    {{ $rapport->temps_moyen_acceptation_h ?? '—' }}<span class="stat-suffix">{{ $rapport->temps_moyen_acceptation_h ? 'h' : '' }}</span>
                </div>
            </div>
            <div class="stat c-gray">
                <div class="stat-label">Ordures collectées</div>
                <div class="stat-value v-gray">
                    {{ $rapport->quantiteOrdure ?? '—' }}<span class="stat-suffix">{{ $rapport->quantiteOrdure ? 't' : '' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Agents -->
    <div class="section">
        <div class="section-title">👷 Agents</div>
        <div class="grid grid-6">
            <div class="stat c-blue">
                <div class="stat-label">Total</div>
                <div class="stat-value v-blue">{{ $rapport->total_agents }}</div>
            </div>
            <div class="stat {{ $rapport->agents_disponibles >= 3 ? 'c-green' : ($rapport->agents_disponibles >= 1 ? 'c-amber' : 'c-red') }}">
                <div class="stat-label">Disponibles</div>
                <div class="stat-value {{ $rapport->agents_disponibles >= 3 ? 'v-green' : ($rapport->agents_disponibles >= 1 ? 'v-amber' : 'v-red') }}">
                    {{ $rapport->agents_disponibles }}
                </div>
            </div>
            <div class="stat c-amber">
                <div class="stat-label">Occupés</div>
                <div class="stat-value v-amber">{{ $rapport->agents_occupes }}</div>
            </div>
            <div class="stat {{ $rapport->agents_absents > 0 ? 'c-red' : 'c-green' }}">
                <div class="stat-label">Absents</div>
                <div class="stat-value {{ $rapport->agents_absents > 0 ? 'v-red' : 'v-green' }}">{{ $rapport->agents_absents }}</div>
            </div>
            <div class="stat {{ $rapport->agents_inactifs > 0 ? 'c-red' : 'c-green' }}">
                <div class="stat-label">Inactifs</div>
                <div class="stat-value {{ $rapport->agents_inactifs > 0 ? 'v-red' : 'v-green' }}">{{ $rapport->agents_inactifs }}</div>
            </div>
            <div class="stat {{ $rapport->taux_presence >= 80 ? 'c-green' : ($rapport->taux_presence >= 60 ? 'c-amber' : 'c-red') }}">
                <div class="stat-label">Taux de présence</div>
                <div class="stat-value {{ $rapport->taux_presence >= 80 ? 'v-green' : ($rapport->taux_presence >= 60 ? 'v-amber' : 'v-red') }}">
                    {{ $rapport->taux_presence }}<span class="stat-suffix">%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Zones -->
    <div class="section">
        <div class="section-title">🗺️ Zones</div>
        <div class="grid grid-2" style="max-width: 400px;">
            <div class="stat c-blue">
                <div class="stat-label">Total zones</div>
                <div class="stat-value v-blue">{{ $rapport->total_zones }}</div>
            </div>
            <div class="stat {{ $rapport->zones_critiques === 0 ? 'c-green' : 'c-red' }}">
                <div class="stat-label">Zones critiques</div>
                <div class="stat-value {{ $rapport->zones_critiques === 0 ? 'v-green' : 'v-red' }}">{{ $rapport->zones_critiques }}</div>
                <div class="stat-desc">> 5 signalements actifs</div>
            </div>
        </div>
    </div>

    @if($rapport->notes)
    <!-- Notes -->
    <div class="section">
        <div class="section-title">📝 Notes et observations</div>
        <div class="notes-box">{{ $rapport->notes }}</div>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <span>SmartCity — Système de gestion des signalements urbains</span>
        <span>Rapport #{{ $rapport->id }} — {{ optional($rapport->dateGeneration)->format('d/m/Y') }}</span>
    </div>

</body>
</html>

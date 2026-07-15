<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport — {{ optional($rapport->dateGeneration)->format('d/m/Y') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800;14..32,900&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
            font-size: 12.5px;
            color: #1e293b;
            background: #f1f5f9;
            padding: 0;
        }

        /* ===== Page wrapper ===== */
        .page {
            max-width: 1100px;
            margin: 0 auto;
            background: #ffffff;
            box-shadow: 0 20px 60px rgba(0,0,0,0.08);
            min-height: 100vh;
        }

        /* ===== Header ===== */
        .header {
            background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 50%, #3b82f6 100%);
            padding: 32px 40px 28px;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .header::before {
            content: '';
            position: absolute;
            top: -60%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.06) 0%, transparent 70%);
            border-radius: 50%;
        }
        .header::after {
            content: '';
            position: absolute;
            bottom: -40%;
            left: 20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.04) 0%, transparent 70%);
            border-radius: 50%;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 18px;
            position: relative;
            z-index: 1;
        }
        .header-logo {
            height: 48px;
            width: auto;
            filter: brightness(0) invert(1);
        }
        .header-brand {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
            line-height: 1;
        }
        .header-brand small {
            display: block;
            font-size: 11px;
            font-weight: 500;
            opacity: 0.75;
            letter-spacing: 0.3px;
            margin-top: 2px;
        }
        .header-right {
            text-align: right;
            position: relative;
            z-index: 1;
            font-size: 11.5px;
            line-height: 1.6;
            opacity: 0.9;
        }
        .header-right strong {
            display: block;
            font-size: 15px;
            font-weight: 700;
            opacity: 1;
        }

        /* ===== Header meta bar ===== */
        .meta-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 40px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11.5px;
            color: #64748b;
        }
        .meta-bar .badge {
            display: inline-block;
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            color: #fff;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 3px 10px;
            border-radius: 20px;
        }
        .meta-bar .date {
            font-weight: 600;
            color: #334155;
        }

        /* ===== Content ===== */
        .content { padding: 28px 40px 20px; }

        /* ===== Section ===== */
        .section { margin-bottom: 28px; }
        .section-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
        }
        .section-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }
        .section-icon.blue   { background: #eff6ff; }
        .section-icon.green  { background: #ecfdf5; }
        .section-icon.amber  { background: #fffbeb; }
        .section-icon.purple { background: #f5f3ff; }
        .section-icon.cyan   { background: #ecfeff; }
        .section-title {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.2px;
        }
        .section-title small {
            font-weight: 400;
            color: #94a3b8;
            font-size: 11px;
            margin-left: 6px;
        }

        /* ===== Grid ===== */
        .grid { display: flex; flex-wrap: wrap; gap: 10px; }
        .grid > * { flex: 1 1 140px; min-width: 120px; }

        /* ===== Stat card ===== */
        .stat {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
            display: flex;
            flex-direction: column;
            transition: box-shadow 0.15s;
        }
        .stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 6px;
        }
        .stat-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .stat-label {
            font-size: 9.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #94a3b8;
        }
        .stat-value {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.1;
            letter-spacing: -0.5px;
        }
        .stat-value .suffix {
            font-size: 13px;
            font-weight: 600;
            color: #94a3b8;
        }
        .stat-value .suffix-unit { font-size: 13px; font-weight: 600; }
        .stat-bar {
            height: 3px;
            border-radius: 2px;
            margin-top: 8px;
            width: 100%;
        }

        /* ===== Stat accent colors ===== */
        .stat.accent-blue   { border-left: 3px solid #3b82f6; }
        .stat.accent-green  { border-left: 3px solid #10b981; }
        .stat.accent-amber  { border-left: 3px solid #f59e0b; }
        .stat.accent-red    { border-left: 3px solid #ef4444; }
        .stat.accent-cyan   { border-left: 3px solid #06b6d4; }
        .stat.accent-gray   { border-left: 3px solid #94a3b8; }
        .stat.accent-purple { border-left: 3px solid #8b5cf6; }
        .stat.dot-blue   .stat-dot { background: #3b82f6; }
        .stat.dot-green  .stat-dot { background: #10b981; }
        .stat.dot-amber  .stat-dot { background: #f59e0b; }
        .stat.dot-red    .stat-dot { background: #ef4444; }
        .stat.dot-cyan   .stat-dot { background: #06b6d4; }
        .stat.dot-gray   .stat-dot { background: #94a3b8; }
        .stat.dot-purple .stat-dot { background: #8b5cf6; }
        .text-blue   { color: #2563eb !important; }
        .text-green  { color: #059669 !important; }
        .text-amber  { color: #d97706 !important; }
        .text-red    { color: #dc2626 !important; }
        .text-cyan   { color: #0891b2 !important; }
        .text-gray   { color: #475569 !important; }
        .text-purple { color: #7c3aed !important; }
        .bg-blue   { background: #3b82f6; }
        .bg-green  { background: #10b981; }
        .bg-amber  { background: #f59e0b; }
        .bg-red    { background: #ef4444; }
        .bg-cyan   { background: #06b6d4; }
        .bg-gray   { background: #94a3b8; }
        .bg-purple { background: #8b5cf6; }
        .bg-soft-blue   { background: #eff6ff; }
        .bg-soft-green  { background: #ecfdf5; }
        .bg-soft-amber  { background: #fffbeb; }
        .bg-soft-red    { background: #fef2f2; }
        .bg-soft-cyan   { background: #ecfeff; }
        .bg-soft-gray   { background: #f8fafc; }
        .bg-soft-purple { background: #f5f3ff; }

        /* ===== Two-column layout ===== */
        .row { display: flex; gap: 14px; }
        .row > .col { flex: 1; min-width: 0; }

        /* ===== Notes ===== */
        .notes-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 16px 18px;
            font-size: 12px;
            color: #334155;
            line-height: 1.6;
            white-space: pre-wrap;
            min-height: 60px;
        }

        /* ===== Divider ===== */
        .divider { border: none; border-top: 1px solid #e2e8f0; margin: 24px 0; }

        /* ===== Footer ===== */
        .footer {
            padding: 16px 40px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #94a3b8;
            background: #f8fafc;
        }

        /* ===== Print button ===== */
        .no-print {
            text-align: right;
            padding: 20px 40px 0;
            max-width: 1100px;
            margin: 0 auto;
        }
        .btn-print {
            background: linear-gradient(135deg, #1e3a5f, #2563eb);
            color: #fff;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-size: 13px;
            cursor: pointer;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            box-shadow: 0 4px 12px rgba(37,99,235,0.25);
            transition: transform 0.1s, box-shadow 0.1s;
        }
        .btn-print:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(37,99,235,0.35);
        }

        /* ===== Print ===== */
        @media print {
            body { background: #fff; }
            .page { box-shadow: none; max-width: 100%; }
            .no-print { display: none !important; }
            .stat { break-inside: avoid; box-shadow: none; }
            .header { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .stat-dot { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .stat-bar { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .badge { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .section-icon { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .meta-bar { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }

        @media screen and (max-width: 700px) {
            .header { flex-direction: column; align-items: flex-start; gap: 12px; padding: 24px 20px; }
            .header-right { text-align: left; }
            .meta-bar { flex-direction: column; gap: 6px; align-items: flex-start; padding: 12px 20px; }
            .content { padding: 20px; }
            .row { flex-direction: column; }
            .footer { flex-direction: column; gap: 4px; padding: 12px 20px; }
        }
    </style>
</head>
<body>

    <!-- Print Button (hidden on print) -->
    <div class="no-print">
        <button class="btn-print" onclick="window.print()">
            🖨  Imprimer / Enregistrer en PDF
        </button>
    </div>

    <div class="page">

        <!-- ===== Header ===== -->
        <div class="header">
            <div class="header-left">
                <img src="{{ public_path('images/logo.png') }}" alt="WasteMove" class="header-logo">
                <div class="header-brand">
                    WasteMove
                    <small>Rapport d'activité — Gestion des déchets</small>
                </div>
            </div>
            <div class="header-right">
                <strong>Rapport #{{ $rapport->id }}</strong>
                Généré par {{ trim(($rapport->admin?->prenom ?? '') . ' ' . ($rapport->admin?->name ?? '')) ?: 'Administrateur' }}
                <br>
                Imprimé le {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>

        <!-- ===== Meta bar ===== -->
        <div class="meta-bar">
            <div>
                <span class="badge">{{ optional($rapport->dateGeneration)->format('d/m/Y') }}</span>
                @if($rapport->date_debut && $rapport->date_fin)
                    <span style="margin-left:12px;">
                        Période : <span class="date">{{ $rapport->date_debut->format('d/m/Y') }}</span> →
                        <span class="date">{{ $rapport->date_fin->format('d/m/Y') }}</span>
                    </span>
                @endif
            </div>
            <div style="display:flex;gap:16px;">
                <span>📋 <strong>{{ $rapport->nbrSignalement ?? 0 }}</strong> signalements</span>
                <span>👷 <strong>{{ $rapport->total_agents ?? 0 }}</strong> agents</span>
                <span>🗺️ <strong>{{ $rapport->total_zones ?? 0 }}</strong> zones</span>
            </div>
        </div>

        <!-- ===== Content ===== -->
        <div class="content">

            <!-- ===== Signalements ===== -->
            <div class="section">
                <div class="section-header">
                    <div class="section-icon blue">📋</div>
                    <div class="section-title">Signalements <small>Vue d'ensemble</small></div>
                </div>
                <div class="grid">
                    <div class="stat accent-blue dot-blue">
                        <div class="stat-top">
                            <span class="stat-label">Total</span>
                            <span class="stat-dot"></span>
                        </div>
                        <div class="stat-value">{{ $rapport->nbrSignalement }}</div>
                        <div class="stat-bar bg-blue"></div>
                    </div>
                    <div class="stat accent-amber dot-amber">
                        <div class="stat-top">
                            <span class="stat-label">En attente</span>
                            <span class="stat-dot"></span>
                        </div>
                        <div class="stat-value text-amber">{{ $rapport->nbr_en_attente }}</div>
                        <div class="stat-bar bg-amber"></div>
                    </div>
                    <div class="stat accent-cyan dot-cyan">
                        <div class="stat-top">
                            <span class="stat-label">En cours</span>
                            <span class="stat-dot"></span>
                        </div>
                        <div class="stat-value text-cyan">{{ $rapport->nbr_en_cours }}</div>
                        <div class="stat-bar bg-cyan"></div>
                    </div>
                    <div class="stat accent-green dot-green">
                        <div class="stat-top">
                            <span class="stat-label">Terminés</span>
                            <span class="stat-dot"></span>
                        </div>
                        <div class="stat-value text-green">{{ $rapport->nbr_termines }}</div>
                        <div class="stat-bar bg-green"></div>
                    </div>
                    <div class="stat accent-red dot-red">
                        <div class="stat-top">
                            <span class="stat-label">Rejetés</span>
                            <span class="stat-dot"></span>
                        </div>
                        <div class="stat-value text-red">{{ $rapport->nbr_rejetes }}</div>
                        <div class="stat-bar bg-red"></div>
                    </div>
                    <div class="stat {{ $rapport->nbr_critiques > 0 ? 'accent-red dot-red' : 'accent-green dot-green' }}">
                        <div class="stat-top">
                            <span class="stat-label">Critiques actifs</span>
                            <span class="stat-dot"></span>
                        </div>
                        <div class="stat-value {{ $rapport->nbr_critiques > 0 ? 'text-red' : 'text-green' }}">{{ $rapport->nbr_critiques }}</div>
                        <div class="stat-bar {{ $rapport->nbr_critiques > 0 ? 'bg-red' : 'bg-green' }}"></div>
                    </div>
                </div>
            </div>

            <!-- ===== Performance ===== -->
            <div class="section">
                <div class="section-header">
                    <div class="section-icon green">⚡</div>
                    <div class="section-title">Performance <small>Indicateurs clés</small></div>
                </div>
                <div class="grid">
                    @php
                        $resColor = $rapport->taux_resolution >= 70 ? 'green' : ($rapport->taux_resolution >= 40 ? 'amber' : 'red');
                        $refColor = $rapport->taux_refus <= 10 ? 'green' : ($rapport->taux_refus <= 25 ? 'amber' : 'red');
                        $trtColor = ($rapport->temps_moyen_traitement_h ?? 0) <= 24 ? 'green' : 'amber';
                        $accColor = ($rapport->temps_moyen_acceptation_h ?? 0) <= 1 ? 'green' : (($rapport->temps_moyen_acceptation_h ?? 0) <= 4 ? 'amber' : 'red');
                    @endphp
                    <div class="stat accent-{{ $resColor }} dot-{{ $resColor }}">
                        <div class="stat-top">
                            <span class="stat-label">Taux résolution</span>
                            <span class="stat-dot"></span>
                        </div>
                        <div class="stat-value text-{{ $resColor }}">{{ $rapport->taux_resolution }}<span class="suffix">%</span></div>
                        <div class="stat-bar bg-{{ $resColor }}"></div>
                    </div>
                    <div class="stat accent-{{ $refColor }} dot-{{ $refColor }}">
                        <div class="stat-top">
                            <span class="stat-label">Taux de refus</span>
                            <span class="stat-dot"></span>
                        </div>
                        <div class="stat-value text-{{ $refColor }}">{{ $rapport->taux_refus }}<span class="suffix">%</span></div>
                        <div class="stat-bar bg-{{ $refColor }}"></div>
                    </div>
                    <div class="stat accent-{{ $trtColor }} dot-{{ $trtColor }}">
                        <div class="stat-top">
                            <span class="stat-label">Tps moyen traitement</span>
                            <span class="stat-dot"></span>
                        </div>
                        <div class="stat-value text-{{ $trtColor }}">{{ $rapport->temps_moyen_traitement_h ?? '—' }}<span class="suffix-unit">{{ $rapport->temps_moyen_traitement_h ? 'h' : '' }}</span></div>
                        <div class="stat-bar bg-{{ $trtColor }}"></div>
                    </div>
                    <div class="stat accent-{{ $accColor }} dot-{{ $accColor }}">
                        <div class="stat-top">
                            <span class="stat-label">Tps moyen acceptation</span>
                            <span class="stat-dot"></span>
                        </div>
                        <div class="stat-value text-{{ $accColor }}">{{ $rapport->temps_moyen_acceptation_h ?? '—' }}<span class="suffix-unit">{{ $rapport->temps_moyen_acceptation_h ? 'h' : '' }}</span></div>
                        <div class="stat-bar bg-{{ $accColor }}"></div>
                    </div>
                    <div class="stat accent-gray dot-gray">
                        <div class="stat-top">
                            <span class="stat-label">Ordures collectées</span>
                            <span class="stat-dot"></span>
                        </div>
                        <div class="stat-value text-gray">{{ $rapport->quantiteOrdure ?? '—' }}<span class="suffix-unit">{{ $rapport->quantiteOrdure ? 't' : '' }}</span></div>
                        <div class="stat-bar bg-gray"></div>
                    </div>
                </div>
            </div>

            <!-- ===== Agents & Zones (side by side) ===== -->
            <div class="row">
                <div class="col">
                    <div class="section">
                        <div class="section-header">
                            <div class="section-icon amber">👷</div>
                            <div class="section-title">Agents</div>
                        </div>
                        <div class="grid">
                            @php
                                $dispColor = $rapport->agents_disponibles >= 3 ? 'green' : ($rapport->agents_disponibles >= 1 ? 'amber' : 'red');
                                $absColor  = $rapport->agents_absents > 0 ? 'red' : 'green';
                                $inacColor = $rapport->agents_inactifs > 0 ? 'red' : 'green';
                                $presColor = $rapport->taux_presence >= 80 ? 'green' : ($rapport->taux_presence >= 60 ? 'amber' : 'red');
                            @endphp
                            <div class="stat accent-blue dot-blue">
                                <div class="stat-top">
                                    <span class="stat-label">Total</span>
                                    <span class="stat-dot"></span>
                                </div>
                                <div class="stat-value">{{ $rapport->total_agents }}</div>
                                <div class="stat-bar bg-blue"></div>
                            </div>
                            <div class="stat accent-{{ $dispColor }} dot-{{ $dispColor }}">
                                <div class="stat-top">
                                    <span class="stat-label">Disponibles</span>
                                    <span class="stat-dot"></span>
                                </div>
                                <div class="stat-value text-{{ $dispColor }}">{{ $rapport->agents_disponibles }}</div>
                                <div class="stat-bar bg-{{ $dispColor }}"></div>
                            </div>
                            <div class="stat accent-amber dot-amber">
                                <div class="stat-top">
                                    <span class="stat-label">Occupés</span>
                                    <span class="stat-dot"></span>
                                </div>
                                <div class="stat-value text-amber">{{ $rapport->agents_occupes }}</div>
                                <div class="stat-bar bg-amber"></div>
                            </div>
                            <div class="stat accent-{{ $absColor }} dot-{{ $absColor }}">
                                <div class="stat-top">
                                    <span class="stat-label">Absents</span>
                                    <span class="stat-dot"></span>
                                </div>
                                <div class="stat-value text-{{ $absColor }}">{{ $rapport->agents_absents }}</div>
                                <div class="stat-bar bg-{{ $absColor }}"></div>
                            </div>
                            <div class="stat accent-{{ $inacColor }} dot-{{ $inacColor }}">
                                <div class="stat-top">
                                    <span class="stat-label">Inactifs</span>
                                    <span class="stat-dot"></span>
                                </div>
                                <div class="stat-value text-{{ $inacColor }}">{{ $rapport->agents_inactifs }}</div>
                                <div class="stat-bar bg-{{ $inacColor }}"></div>
                            </div>
                            <div class="stat accent-{{ $presColor }} dot-{{ $presColor }}">
                                <div class="stat-top">
                                    <span class="stat-label">Taux de présence</span>
                                    <span class="stat-dot"></span>
                                </div>
                                <div class="stat-value text-{{ $presColor }}">{{ $rapport->taux_presence }}<span class="suffix">%</span></div>
                                <div class="stat-bar bg-{{ $presColor }}"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="section">
                        <div class="section-header">
                            <div class="section-icon purple">🗺️</div>
                            <div class="section-title">Zones</div>
                        </div>
                        <div class="grid">
                            @php $zColor = $rapport->zones_critiques === 0 ? 'green' : 'red'; @endphp
                            <div class="stat accent-blue dot-blue">
                                <div class="stat-top">
                                    <span class="stat-label">Total zones</span>
                                    <span class="stat-dot"></span>
                                </div>
                                <div class="stat-value">{{ $rapport->total_zones }}</div>
                                <div class="stat-bar bg-blue"></div>
                            </div>
                            <div class="stat accent-{{ $zColor }} dot-{{ $zColor }}">
                                <div class="stat-top">
                                    <span class="stat-label">Zones critiques</span>
                                    <span class="stat-dot"></span>
                                </div>
                                <div class="stat-value text-{{ $zColor }}">{{ $rapport->zones_critiques }}</div>
                                <div class="stat-bar bg-{{ $zColor }}"></div>
                                <div style="font-size:9px;color:#94a3b8;margin-top:3px;">&gt; 5 signalements actifs</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($rapport->notes)
            <!-- ===== Notes ===== -->
            <div class="section">
                <div class="section-header">
                    <div class="section-icon cyan">📝</div>
                    <div class="section-title">Notes et observations</div>
                </div>
                <div class="notes-box">{{ $rapport->notes }}</div>
            </div>
            @endif

        </div>

        <!-- ===== Footer ===== -->
        <div class="footer">
            <span>WasteMove — Système de gestion des déchets urbains</span>
            <span>Rapport #{{ $rapport->id }} — {{ optional($rapport->dateGeneration)->format('d/m/Y') }}</span>
        </div>

    </div>

</body>
</html>

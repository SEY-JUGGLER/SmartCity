<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-gray-950 dark:to-gray-900">
    @include('livewire.partials.agent-nav')
    {{-- Top bar agent (pointage / dispo) --}}
    <nav class="sticky top-16 z-20 bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl border-b border-slate-200 dark:border-gray-800 hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-orange-500 to-amber-600 flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div class="flex items-center gap-2">
                            <img src="{{ asset('images/logo.png') }}" alt="SmartCity" class="h-8 w-auto">
                            <div>
                                <h1 class="text-lg font-bold text-slate-900 dark:text-white">SmartCity</h1>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Espace Agent</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <span class="text-sm text-slate-600 dark:text-slate-300">
                        {{ auth()->user()->prenom }} {{ auth()->user()->name }}
                    </span>
                    @if(auth()->user()->zone)
                        <span class="hidden sm:inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ auth()->user()->zone->nomZone }}
                        </span>
                    @endif
                    <button wire:click="logout" class="flex items-center gap-1.5 px-3 py-1.5 text-sm text-slate-600 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Déconnexion
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        {{-- En-tête --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Bonjour, {{ auth()->user()->prenom }} 👋</h2>
                <p class="text-slate-500 dark:text-slate-400 mt-1">{{ now()->translatedFormat('l d F Y') }}</p>
            </div>
            <div class="flex items-center gap-3">
                @if(!$pointe)
                    <button wire:click="pointer" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium rounded-xl shadow-lg shadow-emerald-200 dark:shadow-emerald-900/30 transition-all hover:scale-105 active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Pointer maintenant
                    </button>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-2 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-300 text-sm font-medium rounded-xl border border-emerald-200 dark:border-emerald-800">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Pointé aujourd'hui
                    </span>
                @endif
                <button wire:click="toggleDisponibilite" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition-all {{ $disponible ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-900/20 dark:text-emerald-300 dark:border-emerald-800 hover:bg-red-50 hover:text-red-700 hover:border-red-200 dark:hover:bg-red-900/20 dark:hover:text-red-300 dark:hover:border-red-800' : 'bg-red-50 text-red-700 border border-red-200 dark:bg-red-900/20 dark:text-red-300 dark:border-red-800 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-200 dark:hover:bg-emerald-900/20 dark:hover:text-emerald-300 dark:hover:border-emerald-800' }}">
                    <span class="w-2 h-2 rounded-full {{ $disponible ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                    {{ $disponible ? 'Disponible' : 'Indisponible' }}
                </button>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-gray-800 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Missions en cours</p>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1">{{ $this->stats['missionsEnCours'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-gray-800 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Missions terminées</p>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1">{{ $this->stats['missionsTerminees'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-gray-800 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Demandes d'assistance</p>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1">{{ $this->stats['supportEnAttente'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-red-50 dark:bg-red-900/20 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-gray-800 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Équipements</p>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1">{{ $this->stats['materielsAttribues'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Contenu Principal --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Interventions du jour --}}
            <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-slate-200 dark:border-gray-800">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-gray-800">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Interventions du jour</h3>
                        <span class="text-xs text-slate-400">{{ count($this->interventionsToday) }} mission(s)</span>
                    </div>
                </div>
                @if(count($this->interventionsToday) > 0)
                    <div class="divide-y divide-slate-100 dark:divide-gray-800">
                        @foreach($this->interventionsToday as $intervention)
                            <div class="px-6 py-4 flex items-center gap-4 hover:bg-slate-50 dark:hover:bg-gray-800/40 transition-colors">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0
                                    {{ $intervention['statut'] === 'terminer' ? 'bg-emerald-50 text-emerald-500' : ($intervention['statut'] === 'enCours' ? 'bg-amber-50 text-amber-500' : 'bg-slate-50 text-slate-400') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-slate-900 dark:text-white truncate">{{ $intervention['description'] }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                        {{ $intervention['categorie']['nom'] ?? 'Non catégorisé' }}
                                        · {{ $intervention['zone']['nomZone'] ?? 'N/A' }}
                                    </p>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium
                                    {{ $intervention['statut'] === 'terminer' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300' : ($intervention['statut'] === 'enCours' ? 'bg-amber-50 text-amber-700 dark:bg-amber-900/20 dark:text-amber-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400') }}">
                                    {{ $intervention['statut'] === 'terminer' ? 'Terminé' : ($intervention['statut'] === 'enCours' ? 'En cours' : 'En attente') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center">
                        <svg class="w-12 h-12 mx-auto text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Aucune intervention aujourd'hui</p>
                    </div>
                @endif
            </div>

            {{-- Sidebar droite --}}
            <div class="space-y-6">
                {{-- Statut de disponibilité --}}
                <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-gray-800">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Mon statut</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600 dark:text-slate-400">Disponibilité</span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $disponible ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300' : 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-300' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $disponible ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                {{ $disponible ? 'Disponible' : 'Indisponible' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600 dark:text-slate-400">Pointage</span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $pointe ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400' }}">
                                {{ $pointe ? 'Pointé' : 'Non pointé' }}
                            </span>
                        </div>
                        <button wire:click="toggleDisponibilite"
                            class="w-full mt-2 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ $disponible ? 'bg-red-50 text-red-700 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-300 dark:hover:bg-red-900/40' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-300 dark:hover:bg-emerald-900/40' }}">
                            {{ $disponible ? 'Se rendre indisponible' : 'Se rendre disponible' }}
                        </button>
                    </div>
                </div>

                {{-- Ma classification --}}
                @php
                    $cl = $this->classification;
                    $clColor = match($cl['color']) {
                        'emerald' => ['bg' => 'bg-emerald-50 dark:bg-emerald-900/20', 'text' => 'text-emerald-700 dark:text-emerald-300', 'border' => 'border-emerald-200 dark:border-emerald-800', 'badge' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300'],
                        'blue'    => ['bg' => 'bg-blue-50 dark:bg-blue-900/20',       'text' => 'text-blue-700 dark:text-blue-300',       'border' => 'border-blue-200 dark:border-blue-800',       'badge' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300'],
                        'amber'   => ['bg' => 'bg-amber-50 dark:bg-amber-900/20',     'text' => 'text-amber-700 dark:text-amber-300',     'border' => 'border-amber-200 dark:border-amber-800',     'badge' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300'],
                        'violet'  => ['bg' => 'bg-violet-50 dark:bg-violet-900/20',   'text' => 'text-violet-700 dark:text-violet-300',   'border' => 'border-violet-200 dark:border-violet-800',   'badge' => 'bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-300'],
                        'red'     => ['bg' => 'bg-red-50 dark:bg-red-900/20',         'text' => 'text-red-700 dark:text-red-300',         'border' => 'border-red-200 dark:border-red-800',         'badge' => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300'],
                        default   => ['bg' => 'bg-slate-50 dark:bg-slate-800/50',     'text' => 'text-slate-700 dark:text-slate-300',     'border' => 'border-slate-200 dark:border-slate-700',     'badge' => 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400'],
                    };
                @endphp
                <div class="{{ $clColor['bg'] }} rounded-2xl p-5 border {{ $clColor['border'] }}">
                    <h3 class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">Ma classification</h3>
                    <div class="flex items-center gap-3">
                        <span class="text-3xl">{{ $cl['emoji'] }}</span>
                        <div>
                            <p class="font-semibold {{ $clColor['text'] }} text-sm">{{ $cl['label'] }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $cl['desc'] }}</p>
                        </div>
                    </div>
                    <a href="{{ route('agent.classement') }}" class="mt-3 inline-flex items-center gap-1 text-xs {{ $clColor['text'] }} hover:underline">
                        Voir le classement →
                    </a>
                </div>

                {{-- Évolution --}}
                <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-gray-800">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Évolution (6 mois)</h3>
                    <div style="height:160px">
                        <canvas id="chart-agent-evolution" data-labels='@json($this->chartData["labels"])' data-terminees='@json($this->chartData["terminees"])' data-attribuees='@json($this->chartData["attribuees"])'></canvas>
                    </div>
                </div>

                {{-- Lien vers missions --}}
                <a href="{{ route('agent.missions.index') }}" class="block bg-gradient-to-r from-orange-500 to-amber-600 rounded-2xl p-5 text-white hover:from-orange-600 hover:to-amber-700 transition-all shadow-lg shadow-orange-200 dark:shadow-orange-900/30">
                    <div class="flex items-center gap-3">
                        <div class="flex-1">
                            <h3 class="font-semibold">Voir mes missions</h3>
                            <p class="text-sm text-orange-100 mt-1">Accédez à la liste complète de vos missions</p>
                        </div>
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
            </div>
        </div>
    </main>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('chart-agent-evolution');
        if (!canvas) return;

        const labels = JSON.parse(canvas.dataset.labels);
        const terminees = JSON.parse(canvas.dataset.terminees);
        const attribuees = JSON.parse(canvas.dataset.attribuees);

        if (typeof Chart === 'undefined') {
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js';
            script.onload = () => renderChart(labels, terminees, attribuees);
            document.head.appendChild(script);
        } else {
            renderChart(labels, terminees, attribuees);
        }

        function renderChart(labels, terminees, attribuees) {
            const dark = document.documentElement.classList.contains('dark');
            const ctx = canvas.getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Terminées',
                            data: terminees,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16,185,129,0.1)',
                            tension: 0.3,
                            fill: true,
                            pointRadius: 3,
                            pointHoverRadius: 5,
                            borderWidth: 2,
                        },
                        {
                            label: 'Attribuées',
                            data: attribuees,
                            borderColor: '#f59e0b',
                            backgroundColor: 'rgba(245,158,11,0.1)',
                            tension: 0.3,
                            fill: true,
                            pointRadius: 3,
                            pointHoverRadius: 5,
                            borderWidth: 2,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                color: dark ? '#94a3b8' : '#64748b',
                                font: { size: 10 },
                                boxWidth: 12,
                                padding: 12,
                            },
                        },
                        tooltip: {
                            backgroundColor: dark ? '#1e293b' : '#fff',
                            titleColor: dark ? '#e2e8f0' : '#1e293b',
                            bodyColor: dark ? '#94a3b8' : '#64748b',
                            borderColor: dark ? '#334155' : '#e2e8f0',
                            borderWidth: 1,
                            padding: 10,
                            cornerRadius: 8,
                        },
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: dark ? '#64748b' : '#94a3b8', font: { size: 10 } },
                        },
                        y: {
                            grid: { color: dark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)' },
                            ticks: { color: dark ? '#64748b' : '#94a3b8', font: { size: 10 }, precision: 0 },
                            beginAtZero: true,
                        },
                    },
                    animation: { duration: 600 },
                },
            });
        }
    });
    </script>
    @endpush
</div>

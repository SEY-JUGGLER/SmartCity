<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-gray-950 dark:to-gray-900">
    @include('livewire.partials.citoyen-nav')
    <nav class="hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <div class="flex items-center gap-2">
                            <img src="{{ asset('images/logo.png') }}" alt="WasteMove" class="h-8 w-auto">
                            <div>
                                <h1 class="text-lg font-bold text-slate-900 dark:text-white">WasteMove</h1>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Espace Citoyen</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <span class="text-sm text-slate-600 dark:text-slate-300">
                        {{ auth()->user()->prenom }} {{ auth()->user()->name }}
                    </span>
                    <a href="{{ route('profile.edit') }}" class="p-2 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="flex items-center gap-1.5 px-3 py-1.5 text-sm text-slate-600 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Déconnexion
                        </button>
                    </form>
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
            <a href="{{ route('citoyen.signalements.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium rounded-xl shadow-lg shadow-emerald-200 dark:shadow-emerald-900/30 transition-all hover:scale-105 active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nouveau signalement
            </a>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-gray-800 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total</p>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1">{{ $this->stats['total'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-slate-50 dark:bg-slate-800 flex items-center justify-center">
                        <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-gray-800 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">En attente</p>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1">{{ $this->stats['enAttente'] }}</p>
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
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">En cours</p>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1">{{ $this->stats['enCours'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-gray-800 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Terminés</p>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1">{{ $this->stats['termines'] }}</p>
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
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Rejetés</p>
                        <p class="text-3xl font-bold text-red-600 dark:text-red-400 mt-1">{{ $this->stats['rejetes'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-red-50 dark:bg-red-900/20 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Contenu Principal --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Signalements récents --}}
            <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-slate-200 dark:border-gray-800">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-gray-800">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Mes signalements récents</h3>
                        <span class="text-xs text-slate-400">{{ count($this->recentSignalements) }} signalement(s)</span>
                    </div>
                </div>
                @if(count($this->recentSignalements) > 0)
                    <div class="divide-y divide-slate-100 dark:divide-gray-800">
                        @foreach($this->recentSignalements as $signalement)
                            <div class="px-6 py-4 flex items-center gap-4 hover:bg-slate-50 dark:hover:bg-gray-800/40 transition-colors">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0
                                    {{ $signalement['statut'] === 'terminer' ? 'bg-emerald-50 text-emerald-500' : ($signalement['statut'] === 'enCours' ? 'bg-blue-50 text-blue-500' : ($signalement['statut'] === 'rejeter' ? 'bg-red-50 text-red-500' : 'bg-amber-50 text-amber-500')) }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-slate-900 dark:text-white truncate">{{ $signalement['description'] }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                        {{ $signalement['categorie']['nom'] ?? 'Non catégorisé' }}
                                        @if($signalement['zone'])
                                            · {{ $signalement['zone']['nomZone'] }}
                                        @endif
                                    </p>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium
                                    {{ $signalement['statut'] === 'terminer' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300' : ($signalement['statut'] === 'enCours' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300' : ($signalement['statut'] === 'rejeter' ? 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-300' : 'bg-amber-50 text-amber-700 dark:bg-amber-900/20 dark:text-amber-300')) }}">
                                    {{ $signalement['statut'] === 'terminer' ? 'Terminé' : ($signalement['statut'] === 'enCours' ? 'En cours' : ($signalement['statut'] === 'rejeter' ? 'Rejeté' : 'En attente')) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center">
                        <svg class="w-12 h-12 mx-auto text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Aucun signalement pour le moment</p>
                        <a href="{{ route('citoyen.signalements.create') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium rounded-xl transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Créer un signalement
                        </a>
                    </div>
                @endif
            </div>

            {{-- Sidebar droite --}}
            <div class="space-y-6">
                {{-- Carte d'information --}}
                <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-gray-800">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Votre activité</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600 dark:text-slate-400">Signalements actifs</span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $this->stats['enCours'] + $this->stats['enAttente'] > 0 ? 'bg-amber-50 text-amber-700 dark:bg-amber-900/20 dark:text-amber-300' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $this->stats['enCours'] + $this->stats['enAttente'] > 0 ? 'bg-amber-500' : 'bg-emerald-500' }}"></span>
                                {{ $this->stats['enCours'] + $this->stats['enAttente'] > 0 ? $this->stats['enCours'] + $this->stats['enAttente'] . ' actif(s)' : 'Aucun actif' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600 dark:text-slate-400">Taux de résolution</span>
                            <span class="text-sm font-semibold text-slate-900 dark:text-white">
                                {{ $this->stats['total'] > 0 ? round(($this->stats['termines'] / $this->stats['total']) * 100) : 0 }}%
                            </span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-gray-800 rounded-full h-2">
                            <div class="bg-emerald-500 h-2 rounded-full transition-all" style="width: {{ $this->stats['total'] > 0 ? ($this->stats['termines'] / $this->stats['total']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>

                {{-- Ma classification --}}
                @php
                    $cl = $this->classification;
                    $clColor = match($cl['color']) {
                        'emerald' => ['bg' => 'bg-emerald-50 dark:bg-emerald-900/20', 'text' => 'text-emerald-700 dark:text-emerald-300', 'border' => 'border-emerald-200 dark:border-emerald-800'],
                        'blue'    => ['bg' => 'bg-blue-50 dark:bg-blue-900/20',       'text' => 'text-blue-700 dark:text-blue-300',       'border' => 'border-blue-200 dark:border-blue-800'],
                        'amber'   => ['bg' => 'bg-amber-50 dark:bg-amber-900/20',     'text' => 'text-amber-700 dark:text-amber-300',     'border' => 'border-amber-200 dark:border-amber-800'],
                        'violet'  => ['bg' => 'bg-violet-50 dark:bg-violet-900/20',   'text' => 'text-violet-700 dark:text-violet-300',   'border' => 'border-violet-200 dark:border-violet-800'],
                        'red'     => ['bg' => 'bg-red-50 dark:bg-red-900/20',         'text' => 'text-red-700 dark:text-red-300',         'border' => 'border-red-200 dark:border-red-800'],
                        default   => ['bg' => 'bg-slate-50 dark:bg-slate-800/50',     'text' => 'text-slate-700 dark:text-slate-300',     'border' => 'border-slate-200 dark:border-slate-700'],
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
                    <a href="{{ route('citoyen.classement') }}" class="mt-3 inline-flex items-center gap-1 text-xs {{ $clColor['text'] }} hover:underline">
                        Voir le classement →
                    </a>
                </div>

                {{-- Évolution --}}
                <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-gray-800">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Évolution (6 mois)</h3>
                    <div style="height:160px">
                        <canvas id="chart-citoyen-evolution" data-labels='@json($this->chartData["labels"])' data-signalements='@json($this->chartData["signalements"])' data-termines='@json($this->chartData["termines"])'></canvas>
                    </div>
                </div>

                {{-- Lien vers mes signalements --}}
                <a href="{{ route('citoyen.signalements.index') }}" class="block bg-gradient-to-r from-emerald-500 to-green-600 rounded-2xl p-5 text-white hover:from-emerald-600 hover:to-green-700 transition-all shadow-lg shadow-emerald-200 dark:shadow-emerald-900/30">
                    <div class="flex items-center gap-3">
                        <div class="flex-1">
                            <h3 class="font-semibold">Voir tous mes signalements</h3>
                            <p class="text-sm text-emerald-100 mt-1">Consultez l'historique complet</p>
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
        const canvas = document.getElementById('chart-citoyen-evolution');
        if (!canvas) return;

        const labels = JSON.parse(canvas.dataset.labels);
        const signalements = JSON.parse(canvas.dataset.signalements);
        const termines = JSON.parse(canvas.dataset.termines);

        if (typeof Chart === 'undefined') {
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js';
            script.onload = () => renderChart(labels, signalements, termines);
            document.head.appendChild(script);
        } else {
            renderChart(labels, signalements, termines);
        }

        function renderChart(labels, signalements, termines) {
            const dark = document.documentElement.classList.contains('dark');
            const ctx = canvas.getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Signalements',
                            data: signalements,
                            backgroundColor: '#10b981',
                            borderRadius: 4,
                            barPercentage: 0.5,
                        },
                        {
                            label: 'Résolus',
                            data: termines,
                            backgroundColor: '#06b6d4',
                            borderRadius: 4,
                            barPercentage: 0.5,
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

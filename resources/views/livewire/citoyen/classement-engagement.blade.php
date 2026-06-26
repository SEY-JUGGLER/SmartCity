<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-gray-950 dark:to-gray-900">
    @include('livewire.partials.citoyen-nav')
    <main class="max-w-5xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-2 text-slate-900 dark:text-white">Classement des citoyens</h1>
        <p class="text-slate-500 dark:text-slate-400 mb-6">Classement basé sur l'engagement civic (signalements + évaluations)</p>

        <div class="flex gap-2 mb-6">
            <button wire:click="$set('tri', 'signalements')"
                class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ $tri === 'signalements' ? 'bg-emerald-500 text-white' : 'bg-white dark:bg-gray-900 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-gray-800' }}">
                Signalements
            </button>
            <button wire:click="$set('tri', 'evaluations')"
                class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ $tri === 'evaluations' ? 'bg-emerald-500 text-white' : 'bg-white dark:bg-gray-900 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-gray-800' }}">
                Évaluations
            </button>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200 dark:border-gray-800 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left w-12">#</th>
                        <th class="px-4 py-3 text-left">Citoyen</th>
                        <th class="px-4 py-3 text-left">Classification</th>
                        <th class="px-4 py-3 text-center">Signalements</th>
                        <th class="px-4 py-3 text-center">Résolus</th>
                        <th class="px-4 py-3 text-center">Évaluations</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-gray-800">
                    @forelse ($citoyens as $index => $citoyen)
                        @php
                            $isMe = $citoyen->id === auth()->id();
                            $c = $citoyen->classification;
                            $badgeClass = match($c['color']) {
                                'emerald' => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200 dark:bg-emerald-900/20 dark:text-emerald-300 dark:ring-emerald-700/50',
                                'blue'    => 'bg-blue-50 text-blue-700 ring-1 ring-blue-200 dark:bg-blue-900/20 dark:text-blue-300 dark:ring-blue-700/50',
                                'amber'   => 'bg-amber-50 text-amber-700 ring-1 ring-amber-200 dark:bg-amber-900/20 dark:text-amber-300 dark:ring-amber-700/50',
                                'violet'  => 'bg-violet-50 text-violet-700 ring-1 ring-violet-200 dark:bg-violet-900/20 dark:text-violet-300 dark:ring-violet-700/50',
                                'red'     => 'bg-red-50 text-red-700 ring-1 ring-red-200 dark:bg-red-900/20 dark:text-red-300 dark:ring-red-700/50',
                                default   => 'bg-slate-100 text-slate-600 ring-1 ring-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:ring-slate-700/50',
                            };
                        @endphp
                        <tr class="{{ $isMe ? 'bg-emerald-50 dark:bg-emerald-900/10' : '' }} transition-colors">
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold
                                    {{ $index === 0 ? 'bg-yellow-400 text-yellow-900' : ($index === 1 ? 'bg-slate-300 text-slate-700' : ($index === 2 ? 'bg-amber-600 text-white' : 'bg-slate-100 dark:bg-gray-800 text-slate-500')) }}">
                                    {{ $index + 1 }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-emerald-400 to-green-600 flex items-center justify-center text-white text-xs font-bold shrink-0">
                                        @if ($citoyen->photo)
                                            <img src="{{ asset('storage/' . $citoyen->photo) }}" class="w-9 h-9 rounded-full object-cover" alt="">
                                        @else
                                            {{ strtoupper(substr($citoyen->nom, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div>
                                        <span class="font-medium text-slate-900 dark:text-white">{{ $citoyen->nom }}</span>
                                        @if ($isMe)
                                            <span class="ml-2 text-xs text-emerald-600 dark:text-emerald-400 font-medium">(Vous)</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium {{ $badgeClass }}">
                                    {{ $c['emoji'] }} {{ $c['label'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="font-semibold text-slate-900 dark:text-white">{{ $citoyen->signalements }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="font-semibold text-slate-900 dark:text-white">{{ $citoyen->termines }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="font-semibold text-slate-900 dark:text-white">{{ $citoyen->evaluations }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-500">Aucun citoyen trouvé</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($monRang !== false)
            @php $me = $citoyens->firstWhere('id', auth()->id()); @endphp
            <div class="mt-6 p-4 bg-emerald-50 dark:bg-emerald-900/10 rounded-2xl border border-emerald-200 dark:border-emerald-800/30">
                <p class="text-sm text-emerald-800 dark:text-emerald-300">
                    Votre position : <strong>{{ $monRang + 1 }}</strong> / {{ count($citoyens) }}
                    — {{ $me->signalements ?? 0 }} signalement(s),
                    {{ $me->evaluations ?? 0 }} évaluation(s)
                    @if ($me)
                        · Classification : <strong>{{ $me->classification['emoji'] }} {{ $me->classification['label'] }}</strong>
                    @endif
                </p>
            </div>
        @endif

        {{-- Légende des classifications --}}
        <div class="mt-6 bg-white dark:bg-gray-900 rounded-2xl border border-slate-200 dark:border-gray-800 p-5">
            <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">Légende des classifications citoyens</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach([
                    ['label' => 'Ambassadeur de la propreté', 'emoji' => '🌟', 'color' => 'emerald', 'desc' => '≥ 5 signalements, taux validation ≥ 60%'],
                    ['label' => 'Sentinelle du quartier',     'emoji' => '👁️',  'color' => 'blue',    'desc' => '≥ 3 signalements dans sa zone'],
                    ['label' => 'Citoyen modèle',             'emoji' => '🏆', 'color' => 'violet',  'desc' => '≥ 5 signalements, validation ≥ 70%, ≥ 2 évaluations'],
                    ['label' => 'Observateur engagé',         'emoji' => '📊', 'color' => 'amber',   'desc' => '≥ 2 évaluations soumises'],
                    ['label' => 'Citoyen à sensibiliser',     'emoji' => '⚠️', 'color' => 'red',     'desc' => '≥ 2 signalements rejetés, taux rejet ≥ 30%'],
                    ['label' => 'Citoyen actif',              'emoji' => '👤', 'color' => 'slate',   'desc' => 'Membre de la communauté'],
                ] as $item)
                    @php
                        $bc = match($item['color']) {
                            'emerald' => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200 dark:bg-emerald-900/20 dark:text-emerald-300',
                            'blue'    => 'bg-blue-50 text-blue-700 ring-1 ring-blue-200 dark:bg-blue-900/20 dark:text-blue-300',
                            'amber'   => 'bg-amber-50 text-amber-700 ring-1 ring-amber-200 dark:bg-amber-900/20 dark:text-amber-300',
                            'violet'  => 'bg-violet-50 text-violet-700 ring-1 ring-violet-200 dark:bg-violet-900/20 dark:text-violet-300',
                            'red'     => 'bg-red-50 text-red-700 ring-1 ring-red-200 dark:bg-red-900/20 dark:text-red-300',
                            default   => 'bg-slate-100 text-slate-600 ring-1 ring-slate-200 dark:bg-slate-800 dark:text-slate-400',
                        };
                    @endphp
                    <div class="flex items-start gap-2">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium {{ $bc }} shrink-0">
                            {{ $item['emoji'] }} {{ $item['label'] }}
                        </span>
                        <span class="text-xs text-slate-500 dark:text-slate-400 pt-0.5">{{ $item['desc'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
</div>

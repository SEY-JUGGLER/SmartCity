<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Mission #{{ $record->id }}</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Cat\u00e9gorie</dt>
                        <dd class="mt-1"><span class="px-2 py-1 bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 rounded-full text-xs font-medium">{{ $record->categorie?->nom ?? '—' }}</span></dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Priorit\u00e9</dt>
                        <dd class="mt-1">
                            @php $p = $record->priorite; $pc = match($p){'critique'=>'danger','moyenne'=>'warning','faible'=>'success',default=>'gray'}; $pl = match($p){'critique'=>'Critique','moyenne'=>'Moyenne','faible'=>'Faible',default=>$p}; @endphp
                            <span class="px-2 py-1 bg-{{$pc}}-100 dark:bg-{{$pc}}-900/30 text-{{$pc}}-700 dark:text-{{$pc}}-300 rounded-full text-xs font-medium">{{ $pl }}</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Statut</dt>
                        <dd class="mt-1">
                            @php $s = $record->statut; $sc = match($s){'enAttente'=>'warning','enCours'=>'info','terminer'=>'success','rejeter'=>'danger',default=>'gray'}; $sl = match($s){'enAttente'=>'En attente','enCours'=>'En cours','terminer'=>'Termin\u00e9','rejeter'=>'Rejet\u00e9',default=>$s}; @endphp
                            <span class="px-2 py-1 bg-{{$sc}}-100 dark:bg-{{$sc}}-900/30 text-{{$sc}}-700 dark:text-{{$sc}}-300 rounded-full text-xs font-medium">{{ $sl }}</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Citoyen</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $record->citoyen?->prenom ?? '—' }} {{ $record->citoyen?->name ?? '—' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Adresse</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $record->position }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $record->description ?? '—' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Coordonn\u00e9es GPS</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            @if($record->latitude && $record->longitude)
                                {{ $record->latitude }}, {{ $record->longitude }}
                            @else
                                <span class="text-gray-400">Non disponible</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            @if($record->photos->count())
            <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Photos du signalement</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($record->photos as $photo)
                    <div class="rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                        <img src="{{ asset('storage/' . $photo->path) }}" alt="Photo {{ $photo->type }}" class="w-full h-32 object-cover">
                        <div class="px-2 py-1 text-xs text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700">
                            {{ match($photo->type) { 'citoyen' => 'Signal\u00e9', 'avant' => 'Avant', 'apres' => 'Apr\u00e8s', default => $photo->type } }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Informations citoyen</h3>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400 font-bold text-lg">
                        {{ substr($record->citoyen?->prenom ?? '?', 0, 1) }}{{ substr($record->citoyen?->name ?? '?', 0, 1) }}
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $record->citoyen?->prenom ?? '—' }} {{ $record->citoyen?->name ?? '—' }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $record->citoyen?->email ?? '—' }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $record->citoyen?->localite ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 space-y-6">
            <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Actions rapides</h3>
                <div class="space-y-3">
                    @if($record->latitude && $record->longitude)
                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $record->latitude }},{{ $record->longitude }}" target="_blank" class="flex items-center gap-2 w-full px-4 py-2 bg-info-500 hover:bg-info-600 text-white rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Lancer la navigation GPS
                    </a>
                    @endif
                </div>
            </div>

            <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Timeline</h3>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 mt-2 rounded-full bg-primary-500"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Attribu\u00e9e</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $record->attribution?->dateHeureAttribution?->format('d/m/Y H:i') ?? '—' }}</p>
                        </div>
                    </div>
                    @if($record->date_resolution)
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 mt-2 rounded-full bg-success-500"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Termin\u00e9e</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $record->date_resolution->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>

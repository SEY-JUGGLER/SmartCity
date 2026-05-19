<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Informations du signalement #{{ $record->id }}</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Cat\u00e9gorie</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            <span class="px-2 py-1 bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 rounded-full text-xs font-medium">
                                {{ $record->categorie?->nom ?? '—' }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Urgence</dt>
                        <dd class="mt-1">
                            @php
                                $prioColor = match($record->priorite) { 'critique' => 'danger', 'moyenne' => 'warning', 'faible' => 'success', default => 'gray' };
                                $prioLabel = match($record->priorite) { 'critique' => 'Critique', 'moyenne' => 'Moyenne', 'faible' => 'Faible', default => $record->priorite };
                            @endphp
                            <span class="px-2 py-1 bg-{{ $prioColor }}-100 dark:bg-{{ $prioColor }}-900/30 text-{{ $prioColor }}-700 dark:text-{{ $prioColor }}-300 rounded-full text-xs font-medium">{{ $prioLabel }}</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Statut</dt>
                        <dd class="mt-1">
                            @php
                                $statColor = match($record->statut) { 'enAttente' => 'warning', 'enCours' => 'info', 'terminer' => 'success', 'rejeter' => 'danger', default => 'gray' };
                                $statLabel = match($record->statut) { 'enAttente' => 'En attente', 'enCours' => 'En cours', 'terminer' => 'R\u00e9solu', 'rejeter' => 'Rejet\u00e9', default => $record->statut };
                            @endphp
                            <span class="px-2 py-1 bg-{{ $statColor }}-100 dark:bg-{{ $statColor }}-900/30 text-{{ $statColor }}-700 dark:text-{{ $statColor }}-300 rounded-full text-xs font-medium">{{ $statLabel }}</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Date</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $record->dateSignalement?->format('d/m/Y') ?? '—' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Adresse</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $record->position }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $record->description ?? '—' }}</dd>
                    </div>
                </dl>
            </div>

            @if($record->photos->count())
            <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Photos</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($record->photos as $photo)
                    <div class="rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                        <img src="{{ asset('storage/' . $photo->path) }}" alt="Photo" class="w-full h-32 object-cover">
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($record->attribution)
            <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Agent affect\u00e9</h3>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400 font-bold text-lg">
                        {{ substr($record->attribution->agent?->prenom ?? '?', 0, 1) }}{{ substr($record->attribution->agent?->name ?? '?', 0, 1) }}
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">
                            {{ $record->attribution->agent?->prenom ?? '—' }} {{ $record->attribution->agent?->name ?? '—' }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Attribu\u00e9 le {{ $record->attribution->dateHeureAttribution?->format('d/m/Y H:i') ?? '—' }}
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="lg:col-span-1 space-y-6">
            <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Timeline</h3>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 mt-2 rounded-full bg-primary-500"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Signalement cr\u00e9\u00e9</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $record->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @if($record->attribution)
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 mt-2 rounded-full bg-info-500"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Agent assign\u00e9</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $record->attribution->dateHeureAttribution?->format('d/m/Y H:i') ?? '—' }}</p>
                        </div>
                    </div>
                    @endif
                    @if($record->date_resolution)
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 mt-2 rounded-full bg-success-500"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Intervention termin\u00e9e</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $record->date_resolution->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @endif
                    @if($record->statut === 'rejeter')
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 mt-2 rounded-full bg-danger-500"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Signalement rejet\u00e9</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $record->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            @if($record->commentaire_agent)
            <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-3 text-gray-900 dark:text-white">Commentaire agent</h3>
                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $record->commentaire_agent }}</p>
            </div>
            @endif

            @if($record->commentaire_admin)
            <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-3 text-gray-900 dark:text-white">Commentaire administrateur</h3>
                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $record->commentaire_admin }}</p>
            </div>
            @endif

            @if($record->evaluation)
            <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-3 text-gray-900 dark:text-white">Votre \u00e9valuation</h3>
                <div class="flex items-center gap-1 mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= $record->evaluation->note ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                @if($record->evaluation->commentaire)
                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $record->evaluation->commentaire }}</p>
                @endif
            </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>

<x-filament-panels::page>
    @php $user = auth()->user(); @endphp
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 text-center">
            <div class="w-16 h-16 mx-auto mb-3 rounded-full {{ $user->pointer ? 'bg-success-100 dark:bg-success-900/30' : 'bg-gray-100 dark:bg-gray-700' }} flex items-center justify-center">
                <svg class="w-8 h-8 {{ $user->pointer ? 'text-success-600 dark:text-success-400' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @if($user->pointer)
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    @else
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    @endif
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $user->pointer ? 'Point\u00e9' : 'Non point\u00e9' }}</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                @if($user->heurePointage)
                {{ $user->heurePointage->format('H:i') }} - {{ $user->heurePointage->diffForHumans() }}
                @else
                Aucun pointage aujourd'hui
                @endif
            </p>
            @if(!$user->pointer)
            <button wire:click="pointer" class="mt-4 w-full px-4 py-2 bg-success-500 hover:bg-success-600 text-white rounded-lg text-sm font-medium transition-colors">
                Pointer maintenant
            </button>
            @endif
        </div>

        <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 text-center">
            <div class="w-16 h-16 mx-auto mb-3 rounded-full {{ $user->disponible ? 'bg-success-100 dark:bg-success-900/30' : 'bg-danger-100 dark:bg-danger-900/30' }} flex items-center justify-center">
                <svg class="w-8 h-8 {{ $user->disponible ? 'text-success-600 dark:text-success-400' : 'text-danger-600 dark:text-danger-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $user->disponible ? 'Disponible' : 'Indisponible' }}</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                @if($user->disponible)
                Vous \u00eates disponible pour les missions
                @else
                Vous n'\u00eates pas disponible
                @endif
            </p>
            @if($user->disponible)
            <button wire:click="desactiverDisponibilite" class="mt-4 w-full px-4 py-2 bg-warning-500 hover:bg-warning-600 text-white rounded-lg text-sm font-medium transition-colors">
                Se rendre indisponible
            </button>
            @else
            <button wire:click="activerDisponibilite" class="mt-4 w-full px-4 py-2 bg-success-500 hover:bg-success-600 text-white rounded-lg text-sm font-medium transition-colors">
                Se rendre disponible
            </button>
            @endif
        </div>

        <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 text-center">
            <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-warning-100 dark:bg-warning-900/30 flex items-center justify-center">
                <svg class="w-8 h-8 text-warning-600 dark:text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Signaler une absence</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Cong\u00e9 ou absence</p>
            <button wire:click="signalerAbsence" class="mt-4 w-full px-4 py-2 bg-danger-500 hover:bg-danger-600 text-white rounded-lg text-sm font-medium transition-colors" onclick="return confirm('Voulez-vous signaler votre absence ?')">
                Signaler absence
            </button>
        </div>

        <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold mb-3 text-gray-900 dark:text-white">R\u00e9sum\u00e9 du jour</h3>
            @php
                $missionsJour = \App\Models\Attribution::where('agent_id', $user->id)
                    ->whereDate('dateHeureAttribution', today())->count();
                $missionsTerminees = \App\Models\Attribution::where('agent_id', $user->id)
                    ->whereDate('dateHeureAttribution', today())
                    ->whereHas('signalement', fn($q) => $q->where('statut', 'terminer'))->count();
            @endphp
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Missions du jour</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $missionsJour }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Termin\u00e9es</span>
                    <span class="font-medium text-success-600 dark:text-success-400">{{ $missionsTerminees }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">En cours</span>
                    <span class="font-medium text-info-600 dark:text-info-400">{{ $missionsJour - $missionsTerminees }}</span>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>

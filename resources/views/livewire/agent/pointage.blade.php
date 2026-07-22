<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-gray-950 dark:to-gray-900">
    @include('livewire.partials.agent-nav')
    <main class="max-w-3xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6 text-slate-900 dark:text-white">Pointage quotidien</h1>
        <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 border border-slate-200 dark:border-gray-800 space-y-4">
            <p class="text-sm text-slate-600">Statut : {{ $user->pointer ? 'Pointé à ' . $user->heurePointage?->format('H:i') : 'Non pointé' }}</p>
            <p class="text-sm text-slate-600">Disponible : {{ $user->disponible ? 'Oui' : 'Non' }}</p>
            <div class="flex flex-wrap gap-3">
                <button wire:click="pointer" class="px-4 py-2 rounded-xl bg-emerald-500 text-white text-sm">Pointer maintenant</button>
                <button wire:click="activerDisponibilite" class="px-4 py-2 rounded-xl border text-sm">Activer disponibilité</button>
                <button wire:click="desactiverDisponibilite" class="px-4 py-2 rounded-xl border text-sm">Désactiver disponibilité</button>
                <button wire:click="signalerAbsence" wire:confirm="Signaler une absence ?" class="px-4 py-2 rounded-xl bg-amber-500 text-white text-sm">Signaler absence</button>
            </div>
        </div>

        <div class="mt-8 bg-white dark:bg-gray-900 rounded-2xl p-6 border border-slate-200 dark:border-gray-800">
            <h2 class="text-lg font-semibold mb-4 text-slate-900 dark:text-white">Historique des pointages</h2>
            @if($historique->isEmpty())
                <p class="text-sm text-slate-500">Aucun historique pour le moment.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-gray-700 text-slate-500">
                                <th class="text-left py-2 pr-4">Date</th>
                                <th class="text-left py-2 pr-4">Action</th>
                                <th class="text-left py-2 pr-4">Pointé</th>
                                <th class="text-left py-2">Disponible</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($historique as $entry)
                                <tr class="border-b border-slate-100 dark:border-gray-800">
                                    <td class="py-2 pr-4 text-slate-700 dark:text-slate-300 whitespace-nowrap">{{ $entry->heure_action->format('d/m/Y H:i') }}</td>
                                    <td class="py-2 pr-4">
                                        @php
                                            $labels = [
                                                'pointer' => ['label' => 'Pointage', 'color' => 'text-emerald-600 dark:text-emerald-400'],
                                                'activer_disponibilite' => ['label' => 'Disponibilité activée', 'color' => 'text-blue-600 dark:text-blue-400'],
                                                'desactiver_disponibilite' => ['label' => 'Disponibilité désactivée', 'color' => 'text-amber-600 dark:text-amber-400'],
                                                'absence' => ['label' => 'Absence signalée', 'color' => 'text-red-600 dark:text-red-400'],
                                            ];
                                            $info = $labels[$entry->action] ?? ['label' => $entry->action, 'color' => ''];
                                        @endphp
                                        <span class="{{ $info['color'] }}">{{ $info['label'] }}</span>
                                    </td>
                                    <td class="py-2 pr-4">
                                        <span class="{{ $entry->pointer ? 'text-emerald-600' : 'text-red-500' }}">{{ $entry->pointer ? 'Oui' : 'Non' }}</span>
                                    </td>
                                    <td class="py-2">
                                        <span class="{{ $entry->disponible ? 'text-emerald-600' : 'text-red-500' }}">{{ $entry->disponible ? 'Oui' : 'Non' }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </main>
</div>

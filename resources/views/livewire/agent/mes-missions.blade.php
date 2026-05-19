<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-gray-950 dark:to-gray-900">
    @include('livewire.partials.agent-nav')
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Mes missions</h1>
            <select wire:model.live="statutFilter" class="rounded-xl border-slate-300 dark:border-gray-600 dark:bg-gray-800 text-sm">
                <option value="">Tous les statuts</option>
                <option value="enAttente">En attente</option>
                <option value="enCours">En cours</option>
                <option value="terminer">Terminé</option>
                <option value="rejeter">Rejeté</option>
            </select>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-slate-200 dark:border-gray-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 dark:bg-gray-800 text-slate-600 dark:text-slate-400">
                        <tr>
                            <th class="px-4 py-3 text-left">#</th>
                            <th class="px-4 py-3 text-left">Catégorie</th>
                            <th class="px-4 py-3 text-left">Adresse</th>
                            <th class="px-4 py-3 text-left">Priorité</th>
                            <th class="px-4 py-3 text-left">Statut</th>
                            <th class="px-4 py-3 text-left">Attribuée le</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-gray-800">
                        @forelse ($missions as $mission)
                            @php $s = $mission->signalement; @endphp
                            <tr class="hover:bg-slate-50 dark:hover:bg-gray-800/50">
                                <td class="px-4 py-3 font-medium">{{ $s->id }}</td>
                                <td class="px-4 py-3">{{ $s->categorie?->nom ?? '—' }}</td>
                                <td class="px-4 py-3 max-w-xs truncate">{{ $s->position }}</td>
                                <td class="px-4 py-3">{{ ucfirst($s->priorite ?? '—') }}</td>
                                <td class="px-4 py-3">{{ match($s->statut) { 'enAttente'=>'En attente', 'enCours'=>'En cours', 'terminer'=>'Terminé', 'rejeter'=>'Rejeté', default=>$s->statut } }}</td>
                                <td class="px-4 py-3">{{ $mission->dateHeureAttribution?->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('agent.missions.show', $s->id) }}" class="text-orange-600 hover:text-orange-700 font-medium">Détails</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-4 py-12 text-center text-slate-500">Aucune mission</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($missions->hasPages())
                <div class="px-4 py-3 border-t border-slate-100 dark:border-gray-800">{{ $missions->links() }}</div>
            @endif
        </div>
    </main>
</div>

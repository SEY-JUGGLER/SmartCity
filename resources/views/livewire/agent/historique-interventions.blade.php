<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-gray-950 dark:to-gray-900">
    @include('livewire.partials.agent-nav')
    <main class="max-w-7xl mx-auto px-4 py-8 px-4">
        <h1 class="text-2xl font-bold mb-6 text-slate-900 dark:text-white">Historique des interventions</h1>
        <select wire:model.live="statutFilter" class="mb-4 rounded-xl border-slate-300 dark:border-gray-600 dark:bg-gray-800 text-sm">
            <option value="">Tous</option>
            <option value="terminer">Terminé</option>
            <option value="rejeter">Rejeté</option>
        </select>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200 dark:border-gray-800 overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 dark:bg-gray-800"><tr>
                    <th class="px-4 py-3 text-left">#</th><th class="px-4 py-3 text-left">Catégorie</th>
                    <th class="px-4 py-3 text-left">Adresse</th><th class="px-4 py-3 text-left">Statut</th>
                    <th class="px-4 py-3 text-left">Note</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-100 dark:divide-gray-800">
                    @forelse ($items as $item)
                        <tr>
                            <td class="px-4 py-3">{{ $item->signalement->id }}</td>
                            <td class="px-4 py-3">{{ $item->signalement->categorie?->nom }}</td>
                            <td class="px-4 py-3">{{ Str::limit($item->signalement->position, 40) }}</td>
                            <td class="px-4 py-3">{{ $item->signalement->statut }}</td>
                            <td class="px-4 py-3">{{ $item->signalement->evaluation?->note ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-slate-500">Aucun historique</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-4 py-3">{{ $items->links() }}</div>
        </div>
    </main>
</div>

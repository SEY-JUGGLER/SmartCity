<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-gray-950 dark:to-gray-900">
    @include('livewire.partials.citoyen-nav')
    <main class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex flex-wrap gap-4 justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Mes signalements</h1>
            <a href="{{ route('citoyen.signalements.create') }}" class="px-4 py-2 rounded-xl bg-emerald-500 text-white text-sm">Nouveau signalement</a>
        </div>
        <div class="flex gap-2 mb-4">
            <select wire:model.live="statutFilter" class="rounded-xl border-slate-300 dark:border-gray-600 dark:bg-gray-800 text-sm">
                <option value="">Tous statuts</option>
                <option value="enAttente">En attente</option>
                <option value="enCours">En cours</option>
                <option value="terminer">Résolu</option>
                <option value="rejeter">Rejeté</option>
            </select>
            <select wire:model.live="prioriteFilter" class="rounded-xl border-slate-300 dark:border-gray-600 dark:bg-gray-800 text-sm">
                <option value="">Toutes priorités</option>
                <option value="faible">Faible</option>
                <option value="moyenne">Moyenne</option>
                <option value="critique">Critique</option>
            </select>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 dark:bg-gray-800"><tr>
                    <th class="px-4 py-3 text-left">#</th><th class="px-4 py-3 text-left">Catégorie</th>
                    <th class="px-4 py-3 text-left">Adresse</th><th class="px-4 py-3 text-left">Statut</th>
                    <th class="px-4 py-3 text-left">Agent</th><th class="px-4 py-3"></th>
                </tr></thead>
                <tbody class="divide-y divide-slate-100 dark:divide-gray-800">
                    @forelse($signalements as $s)
                        <tr>
                            <td class="px-4 py-3">{{ $s->id }}</td>
                            <td class="px-4 py-3">{{ $s->categorie?->nom }}</td>
                            <td class="px-4 py-3">{{ Str::limit($s->position, 35) }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium {{ match($s->statut) {
                                    'enAttente' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300',
                                    'enCours' => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900/30 dark:text-cyan-300',
                                    'terminer' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300',
                                    'rejeter' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300',
                                } }}">
                                    {{ match($s->statut) { 'enAttente'=>'En attente', 'enCours'=>'En cours', 'terminer'=>'Résolu', 'rejeter'=>'Rejeté', default=>$s->statut } }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $s->attribution?->agent ? $s->attribution->agent->prenom . ' ' . $s->attribution->agent->name : '—' }}</td>
                            <td class="px-4 py-3"><a href="{{ route('citoyen.signalements.show', $s->id) }}" class="text-emerald-600 font-medium">Voir</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-slate-500">Aucun signalement</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-4 py-3">{{ $signalements->links() }}</div>
        </div>
    </main>
</div>

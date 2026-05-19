<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-gray-950 dark:to-gray-900">
    @include('livewire.partials.agent-nav')
    <main class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Demandes de support</h1>
            <button wire:click="$toggle('showForm')" class="px-4 py-2 rounded-xl bg-orange-500 text-white text-sm">Nouvelle demande</button>
        </div>
        @if($showForm)
        <form wire:submit="create" class="mb-6 p-4 bg-white dark:bg-gray-900 rounded-2xl border space-y-3">
            <select wire:model="type" class="w-full rounded-xl border-slate-300 dark:border-gray-600 dark:bg-gray-800">
                <option value="">Type</option>
                <option value="renfort">Renfort</option>
                <option value="materiel">Matériel</option>
                <option value="panne_vehicule">Panne véhicule</option>
                <option value="assistance_urgente">Assistance urgente</option>
            </select>
            <textarea wire:model="description" rows="3" class="w-full rounded-xl border-slate-300 dark:border-gray-600 dark:bg-gray-800" placeholder="Description"></textarea>
            <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-500 text-white text-sm">Envoyer</button>
        </form>
        @endif
        <div class="bg-white dark:bg-gray-900 rounded-2xl border overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 dark:bg-gray-800"><tr>
                    <th class="px-4 py-3 text-left">#</th><th class="px-4 py-3 text-left">Type</th>
                    <th class="px-4 py-3 text-left">Description</th><th class="px-4 py-3 text-left">Statut</th>
                    <th class="px-4 py-3"></th>
                </tr></thead>
                <tbody class="divide-y divide-slate-100 dark:divide-gray-800">
                    @forelse($requests as $r)
                        <tr>
                            <td class="px-4 py-3">{{ $r->id }}</td>
                            <td class="px-4 py-3">{{ $r->type }}</td>
                            <td class="px-4 py-3">{{ Str::limit($r->description, 50) }}</td>
                            <td class="px-4 py-3">{{ $r->statut }}</td>
                            <td class="px-4 py-3">@if($r->statut === 'en_attente')<button wire:click="cancel({{ $r->id }})" class="text-red-600 text-xs">Annuler</button>@endif</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-slate-500">Aucune demande</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-4 py-3">{{ $requests->links() }}</div>
        </div>
    </main>
</div>

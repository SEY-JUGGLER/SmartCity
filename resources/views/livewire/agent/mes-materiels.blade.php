<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-gray-950 dark:to-gray-900">
    @include('livewire.partials.agent-nav')
    <main class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6 text-slate-900 dark:text-white">Mes matériels</h1>
        <select wire:model.live="statutFilter" class="mb-4 rounded-xl border-slate-300 dark:border-gray-600 dark:bg-gray-800 text-sm">
            <option value="">Tous</option>
            <option value="disponible">Disponible</option>
            <option value="attribue">Attribué</option>
            <option value="en_maintenance">En maintenance</option>
            <option value="hors_service">Hors service</option>
        </select>
        <div class="grid gap-4 sm:grid-cols-2">
            @forelse($materiels as $m)
                <div class="p-4 bg-white dark:bg-gray-900 rounded-2xl border border-slate-200 dark:border-gray-800">
                    <h3 class="font-semibold">{{ $m->nom }}</h3>
                    <p class="text-sm text-slate-500 mt-1">{{ $m->description }}</p>
                    <p class="text-xs mt-2"><span class="font-medium">{{ $m->categorie }}</span> · {{ $m->statut }}</p>
                </div>
            @empty
                <p class="text-slate-500 col-span-2">Aucun matériel</p>
            @endforelse
        </div>
        <div class="mt-4">{{ $materiels->links() }}</div>
    </main>
</div>

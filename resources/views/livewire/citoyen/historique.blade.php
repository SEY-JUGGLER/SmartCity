<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-gray-950 dark:to-gray-900">
    @include('livewire.partials.citoyen-nav')
    <main class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6 text-slate-900 dark:text-white">Historique</h1>
        <div class="space-y-3">
            @forelse($signalements as $s)
                <a href="{{ route('citoyen.signalements.show', $s->id) }}" class="block p-4 bg-white dark:bg-gray-900 rounded-2xl border border-slate-200 dark:border-gray-800 hover:border-emerald-300">
                    <div class="flex justify-between">
                        <span class="font-medium">#{{ $s->id }} — {{ $s->categorie?->nom }}</span>
                        <span class="text-xs text-slate-500">{{ $s->dateSignalement?->format('d/m/Y') }}</span>
                    </div>
                    <p class="text-sm text-slate-600 mt-1">{{ Str::limit($s->position, 50) }}</p>
                </a>
            @empty
                <p class="text-slate-500 text-center py-8">Aucun historique</p>
            @endforelse
        </div>
        <div class="mt-4">{{ $signalements->links() }}</div>
    </main>
</div>

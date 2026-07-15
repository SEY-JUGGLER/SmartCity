<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-gray-950 dark:to-gray-900">
    @include('livewire.partials.citoyen-nav')
    <main class="max-w-3xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Notifications</h1>
            <button wire:click="marquerToutesLues" class="text-sm text-emerald-600">Tout marquer lu</button>
        </div>
        <div class="space-y-2">
            @forelse($notifications as $n)
                <div class="p-4 rounded-xl border {{ $n->read_at ? 'bg-slate-50 dark:bg-gray-800/50' : 'bg-white dark:bg-gray-900 border-emerald-200' }}">
                    <p class="text-sm font-medium">{{ $n->data['title'] ?? class_basename($n->type) }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $n->data['body'] ?? '' }}</p>
                    <p class="text-xs text-slate-400 mt-1">{{ $n->created_at->diffForHumans() }}</p>
                    @unless($n->read_at)
                        <button wire:click="marquerLue('{{ $n->id }}')" class="text-xs text-emerald-600 mt-2">Marquer comme lu</button>
                    @endunless
                </div>
            @empty
                <p class="text-slate-500 text-center py-8">Aucune notification</p>
            @endforelse
        </div>
        {{ $notifications->links() }}
    </main>
</div>

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
    </main>
</div>

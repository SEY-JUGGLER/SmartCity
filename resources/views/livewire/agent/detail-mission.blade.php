<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-gray-950 dark:to-gray-900">
    @include('livewire.partials.agent-nav')
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-wrap gap-3 mb-6">
            <a href="{{ route('agent.missions.index') }}" class="px-4 py-2 text-sm rounded-xl border border-slate-300 dark:border-gray-600 text-slate-700 dark:text-slate-300">← Retour</a>
            @if ($record->statut === 'enAttente')
                <button wire:click="accepter" wire:confirm="Accepter cette mission ?" class="px-4 py-2 text-sm rounded-xl bg-emerald-500 text-white">Accepter</button>
                <button wire:click="$set('showRefusModal', true)" class="px-4 py-2 text-sm rounded-xl bg-red-500 text-white">Refuser</button>
            @endif
            @if ($record->statut === 'enCours')
                <button wire:click="$set('showTerminerModal', true)" class="px-4 py-2 text-sm rounded-xl bg-emerald-600 text-white">Terminer</button>
                <button wire:click="$set('showDifficulteModal', true)" class="px-4 py-2 text-sm rounded-xl bg-amber-500 text-white">Difficulté</button>
                <button wire:click="$set('showImpossibleModal', true)" class="px-4 py-2 text-sm rounded-xl bg-red-600 text-white">Mission impossible</button>
            @endif
            @if ($record->latitude && $record->longitude)
                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $record->latitude }},{{ $record->longitude }}" target="_blank" class="px-4 py-2 text-sm rounded-xl bg-blue-500 text-white">Navigation GPS</a>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl border border-slate-200 dark:border-gray-800">
                    <h2 class="text-xl font-bold mb-4 text-slate-900 dark:text-white">Mission #{{ $record->id }}</h2>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div><dt class="text-slate-500">Catégorie</dt><dd class="font-medium">{{ $record->categorie?->nom ?? '—' }}</dd></div>
                        <div><dt class="text-slate-500">Priorité</dt><dd class="font-medium">{{ ucfirst($record->priorite) }}</dd></div>
                        <div><dt class="text-slate-500">Statut</dt><dd class="font-medium">{{ match($record->statut) { 'enAttente'=>'En attente', 'enCours'=>'En cours', 'terminer'=>'Terminé', 'rejeter'=>'Rejeté', default=>$record->statut } }}</dd></div>
                        <div><dt class="text-slate-500">Citoyen</dt><dd class="font-medium">{{ $record->citoyen?->prenom }} {{ $record->citoyen?->name }}</dd></div>
                        <div class="sm:col-span-2"><dt class="text-slate-500">Adresse</dt><dd>{{ $record->position }}</dd></div>
                        <div class="sm:col-span-2"><dt class="text-slate-500">Description</dt><dd>{{ $record->description }}</dd></div>
                    </dl>
                </div>
                @if ($record->photos->count())
                <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl border border-slate-200 dark:border-gray-800">
                    <h3 class="font-semibold mb-4 text-slate-900 dark:text-white">Photos</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach ($record->photos as $photo)
                            <img src="{{ asset('storage/' . $photo->path) }}" alt="" class="w-full h-32 object-cover rounded-lg">
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl border border-slate-200 dark:border-gray-800 h-fit">
                <h3 class="font-semibold mb-4">Timeline</h3>
                <p class="text-sm text-slate-600">Attribuée : {{ $record->attribution?->dateHeureAttribution?->format('d/m/Y H:i') ?? '—' }}</p>
                @if ($record->date_resolution)
                    <p class="text-sm text-slate-600 mt-2">Terminée : {{ $record->date_resolution->format('d/m/Y H:i') }}</p>
                @endif
            </div>
        </div>
    </main>

    @if ($showRefusModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 w-full max-w-md">
            <h3 class="font-semibold mb-4">Refuser la mission</h3>
            <textarea wire:model="motifRefus" rows="3" class="w-full rounded-xl border-slate-300 dark:border-gray-600 dark:bg-gray-800" placeholder="Motif du refus"></textarea>
            @error('motifRefus') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            <div class="flex gap-2 mt-4 justify-end">
                <button wire:click="$set('showRefusModal', false)" class="px-4 py-2 rounded-xl border">Annuler</button>
                <button wire:click="refuser" class="px-4 py-2 rounded-xl bg-red-500 text-white">Confirmer</button>
            </div>
        </div>
    </div>
    @endif

    @if ($showTerminerModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 w-full max-w-md">
            <h3 class="font-semibold mb-4">Terminer l'intervention</h3>
            <textarea wire:model="commentaireTerminer" rows="4" class="w-full rounded-xl border-slate-300 dark:border-gray-600 dark:bg-gray-800 mb-3" placeholder="Compte-rendu"></textarea>
            <input type="file" wire:model="photosApres" multiple accept="image/*" class="w-full text-sm">
            <div class="flex gap-2 mt-4 justify-end">
                <button wire:click="$set('showTerminerModal', false)" class="px-4 py-2 rounded-xl border">Annuler</button>
                <button wire:click="terminer" class="px-4 py-2 rounded-xl bg-emerald-500 text-white">Terminer</button>
            </div>
        </div>
    </div>
    @endif

    @if ($showDifficulteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 w-full max-w-md space-y-3">
            <h3 class="font-semibold">Signaler une difficulté</h3>
            <select wire:model="difficulteType" class="w-full rounded-xl border-slate-300 dark:border-gray-600 dark:bg-gray-800">
                <option value="">Type</option>
                <option value="acces">Accès impossible</option>
                <option value="materiel">Manque de matériel</option>
                <option value="renfort">Besoin de renfort</option>
                <option value="autre">Autre</option>
            </select>
            <textarea wire:model="difficulteDescription" rows="3" class="w-full rounded-xl border-slate-300 dark:border-gray-600 dark:bg-gray-800"></textarea>
            <div class="flex gap-2 justify-end">
                <button wire:click="$set('showDifficulteModal', false)" class="px-4 py-2 rounded-xl border">Annuler</button>
                <button wire:click="signalerDifficulte" class="px-4 py-2 rounded-xl bg-amber-500 text-white">Envoyer</button>
            </div>
        </div>
    </div>
    @endif

    @if ($showImpossibleModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 w-full max-w-md">
            <h3 class="font-semibold mb-4">Mission impossible</h3>
            <textarea wire:model="motifImpossible" rows="3" class="w-full rounded-xl border-slate-300 dark:border-gray-600 dark:bg-gray-800"></textarea>
            <div class="flex gap-2 mt-4 justify-end">
                <button wire:click="$set('showImpossibleModal', false)" class="px-4 py-2 rounded-xl border">Annuler</button>
                <button wire:click="missionImpossible" class="px-4 py-2 rounded-xl bg-red-500 text-white">Confirmer</button>
            </div>
        </div>
    </div>
    @endif
</div>

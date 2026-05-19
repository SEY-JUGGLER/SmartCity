<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-gray-950 dark:to-gray-900">
    @include('livewire.partials.citoyen-nav')
    <main class="max-w-4xl mx-auto px-4 py-8">
        <a href="{{ route('citoyen.signalements.index') }}" class="text-sm text-emerald-600 mb-4 inline-block">← Retour</a>
        <h1 class="text-2xl font-bold mb-6 text-slate-900 dark:text-white">Signalement #{{ $record->id }}</h1>
        <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 border border-slate-200 dark:border-gray-800 space-y-4">
            <p><strong>Catégorie :</strong> {{ $record->categorie?->nom }}</p>
            <p><strong>Statut :</strong> {{ $record->statut }}</p>
            <p><strong>Priorité :</strong> {{ $record->priorite }}</p>
            <p><strong>Adresse :</strong> {{ $record->position }}</p>
            <p><strong>Description :</strong> {{ $record->description }}</p>
            @if($record->attribution?->agent)
                <p><strong>Agent :</strong> {{ $record->attribution->agent->prenom }} {{ $record->attribution->agent->name }}</p>
            @endif
            @if($record->photos->count())
                <div class="grid grid-cols-3 gap-2">
                    @foreach($record->photos as $photo)
                        <img src="{{ asset('storage/' . $photo->path) }}" class="rounded-lg h-24 w-full object-cover" alt="">
                    @endforeach
                </div>
            @endif
            @if($record->statut === 'terminer' && !$record->evaluation)
                <div class="flex gap-2 pt-4">
                    <button wire:click="$set('showEvalModal', true)" class="px-4 py-2 rounded-xl bg-emerald-500 text-white text-sm">Évaluer</button>
                    <button wire:click="signalerNonResolu" wire:confirm="Confirmer que le problème n'est pas résolu ?" class="px-4 py-2 rounded-xl bg-red-500 text-white text-sm">Non résolu</button>
                </div>
            @elseif($record->evaluation)
                <p class="text-sm text-slate-600 pt-2">Note : {{ $record->evaluation->note }}/5 — {{ $record->evaluation->commentaire }}</p>
            @endif
        </div>
    </main>
    @if($showEvalModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <form wire:submit="evaluer" class="bg-white dark:bg-gray-900 rounded-2xl p-6 w-full max-w-md space-y-3">
            <h3 class="font-semibold">Évaluer l'intervention</h3>
            <select wire:model="note" class="w-full rounded-xl border-slate-300 dark:border-gray-600 dark:bg-gray-800">
                <option value="">Note</option>
                @for($i = 1; $i <= 5; $i++)<option value="{{ $i }}">{{ $i }} étoile(s)</option>@endfor
            </select>
            <textarea wire:model="commentaire" rows="3" class="w-full rounded-xl border-slate-300 dark:border-gray-600 dark:bg-gray-800" placeholder="Commentaire"></textarea>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="probleme_resolu"> Problème résolu</label>
            <div class="flex gap-2 justify-end">
                <button type="button" wire:click="$set('showEvalModal', false)" class="px-4 py-2 rounded-xl border">Annuler</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-500 text-white">Envoyer</button>
            </div>
        </form>
    </div>
    @endif
</div>

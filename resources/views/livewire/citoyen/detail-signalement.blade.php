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
                <div class="flex items-center gap-3 p-3 bg-blue-50 dark:bg-blue-900/10 rounded-xl border border-blue-200 dark:border-blue-800/30">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-sm font-bold shrink-0">
                        @if($record->attribution->agent->photoProfi)
                            <img src="{{ asset('storage/' . $record->attribution->agent->photoProfi) }}" class="w-10 h-10 rounded-full object-cover" alt="">
                        @else
                            {{ strtoupper(substr($record->attribution->agent->prenom, 0, 1)) }}{{ strtoupper(substr($record->attribution->agent->name, 0, 1)) }}
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-900 dark:text-white">Agent : {{ $record->attribution->agent->prenom }} {{ $record->attribution->agent->name }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">En charge de votre signalement</p>
                    </div>
                </div>
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
                    <button wire:click="openEvalModal" class="px-4 py-2 rounded-xl bg-emerald-500 text-white text-sm">Évaluer l'agent</button>
                    <button wire:click="signalerNonResolu" wire:confirm="Confirmer que le problème n'est pas résolu ?" class="px-4 py-2 rounded-xl bg-red-500 text-white text-sm">Non résolu</button>
                </div>
            @elseif($record->evaluation)
                <div class="p-4 bg-green-50 dark:bg-green-900/10 rounded-xl border border-green-200 dark:border-green-800/30 mt-2">
                    <p class="text-sm font-medium text-green-800 dark:text-green-300 mb-1">
                        @if($record->attribution?->agent)
                            Évaluation de l'agent {{ $record->attribution->agent->prenom }} {{ $record->attribution->agent->name }}
                        @else
                            Votre évaluation
                        @endif
                    </p>
                    <div class="flex items-center gap-1 mb-1">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="text-lg {{ $i <= $record->evaluation->note ? 'text-yellow-500' : 'text-slate-300 dark:text-slate-600' }}">★</span>
                        @endfor
                        <span class="ml-2 text-sm font-semibold text-slate-900 dark:text-white">{{ $record->evaluation->note }}/5</span>
                    </div>
                    @if($record->evaluation->commentaire)
                        <p class="text-sm text-slate-600 dark:text-slate-400 italic">"{{ $record->evaluation->commentaire }}"</p>
                    @endif
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        Problème {{ $record->evaluation->probleme_resolu ? 'résolu' : 'non résolu' }}
                    </p>
                    <button wire:click="openEditModal" class="mt-2 text-xs text-emerald-600 dark:text-emerald-400 hover:underline">Modifier mon évaluation</button>
                </div>
            @endif
        </div>
    </main>
    @if($showEvalModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <form wire:submit="{{ $editing ? 'modifierEvaluation' : 'evaluer' }}" class="bg-white dark:bg-gray-900 rounded-2xl p-6 w-full max-w-md space-y-3">
            <h3 class="font-semibold">{{ $editing ? 'Modifier votre évaluation' : "Évaluer l'agent" }}</h3>
            @if($record->attribution?->agent)
                <div class="flex items-center gap-3 p-3 bg-blue-50 dark:bg-blue-900/10 rounded-xl">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-sm font-bold shrink-0">
                        @if($record->attribution->agent->photoProfi)
                            <img src="{{ asset('storage/' . $record->attribution->agent->photoProfi) }}" class="w-10 h-10 rounded-full object-cover" alt="">
                        @else
                            {{ strtoupper(substr($record->attribution->agent->prenom, 0, 1)) }}{{ strtoupper(substr($record->attribution->agent->name, 0, 1)) }}
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $record->attribution->agent->prenom }} {{ $record->attribution->agent->name }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Agent en charge</p>
                    </div>
                </div>
            @endif
            <select wire:model="note" class="w-full rounded-xl border-slate-300 dark:border-gray-600 dark:bg-gray-800">
                <option value="">Note (1-5)</option>
                @for($i = 1; $i <= 5; $i++)<option value="{{ $i }}">{{ $i }} étoile(s)</option>@endfor
            </select>
            <textarea wire:model="commentaire" rows="3" class="w-full rounded-xl border-slate-300 dark:border-gray-600 dark:bg-gray-800" placeholder="Commentaire sur l'intervention de l'agent"></textarea>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="probleme_resolu"> Problème résolu</label>
            <div class="flex gap-2 justify-end">
                <button type="button" wire:click="$set('showEvalModal', false)" class="px-4 py-2 rounded-xl border">Annuler</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-500 text-white">{{ $editing ? 'Modifier' : 'Envoyer' }}</button>
            </div>
        </form>
    </div>
    @endif
</div>

<?php

namespace App\Livewire\Citoyen;

use App\Livewire\Concerns\HandlesFlashMessages;
use App\Models\Evaluation;
use App\Models\Signalement;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.citoyen')]
class DetailSignalement extends Component
{
    use HandlesFlashMessages;

    public Signalement $record;
    public ?int $note = null;
    public string $commentaire = '';
    public bool $probleme_resolu = true;
    public bool $showEvalModal = false;

    public function mount(Signalement $signalement): void
    {
        abort_unless(Auth::user()?->isCitoyen(), 403);
        $this->record = Signalement::with(['categorie', 'zone', 'attribution.agent', 'photos', 'evaluation'])
            ->findOrFail($signalement->id);
        if ($this->record->user_id !== Auth::id()) {
            abort(403);
        }
    }

    public function evaluer(): void
    {
        $this->validate(['note' => 'required|integer|min:1|max:5']);
        Evaluation::create([
            'signalement_id' => $this->record->id,
            'user_id' => Auth::id(),
            'note' => $this->note,
            'commentaire' => $this->commentaire ?: null,
            'probleme_resolu' => $this->probleme_resolu,
        ]);
        $this->record->refresh();
        $this->showEvalModal = false;
        $this->flashSuccess('Merci pour votre évaluation.');
    }

    public function signalerNonResolu(): void
    {
        Evaluation::create([
            'signalement_id' => $this->record->id,
            'user_id' => Auth::id(),
            'note' => 1,
            'commentaire' => 'Le problème n\'est pas résolu',
            'probleme_resolu' => false,
        ]);
        $this->record->refresh();
        $this->flashWarning('Votre retour a été transmis.');
    }

    public function render()
    {
        return view('livewire.citoyen.detail-signalement');
    }
}

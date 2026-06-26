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
    public bool $editing = false;

    public function mount(Signalement $signalement): void
    {
        abort_unless(Auth::user()?->isCitoyen(), 403);
        $this->record = Signalement::with(['categorie', 'zone', 'attribution.agent', 'photos', 'evaluation'])
            ->findOrFail($signalement->id);
        if ($this->record->user_id !== Auth::id()) {
            abort(403);
        }
    }

    public function openEvalModal(): void
    {
        $this->editing = false;
        $this->note = null;
        $this->commentaire = '';
        $this->probleme_resolu = true;
        $this->showEvalModal = true;
    }

    public function openEditModal(): void
    {
        $eval = $this->record->evaluation;
        $this->editing = true;
        $this->note = $eval->note;
        $this->commentaire = $eval->commentaire ?? '';
        $this->probleme_resolu = $eval->probleme_resolu;
        $this->showEvalModal = true;
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

    public function modifierEvaluation(): void
    {
        $this->validate(['note' => 'required|integer|min:1|max:5']);
        $this->record->evaluation->update([
            'note' => $this->note,
            'commentaire' => $this->commentaire ?: null,
            'probleme_resolu' => $this->probleme_resolu,
        ]);
        $this->record->refresh();
        $this->showEvalModal = false;
        $this->flashSuccess('Évaluation modifiée avec succès.');
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

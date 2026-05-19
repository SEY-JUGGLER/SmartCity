<?php

namespace App\Livewire\Agent;

use App\Livewire\Concerns\HandlesFlashMessages;
use App\Models\Signalement;
use App\Models\SignalementPhoto;
use App\Models\SupportRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.agent')]
class DetailMission extends Component
{
    use HandlesFlashMessages;
    use WithFileUploads;

    public Signalement $record;

    public string $motifRefus = '';
    public string $commentaireTerminer = '';
    public array $photosApres = [];
    public string $difficulteType = '';
    public string $difficulteDescription = '';
    public string $motifImpossible = '';

    public bool $showRefusModal = false;
    public bool $showTerminerModal = false;
    public bool $showDifficulteModal = false;
    public bool $showImpossibleModal = false;

    public function mount(Signalement $signalement): void
    {
        abort_unless(Auth::user()?->isAgent(), 403);

        $this->record = Signalement::with(['categorie', 'zone', 'attribution.admin', 'citoyen', 'photos'])
            ->findOrFail($signalement->id);

        $attribution = $this->record->attribution;
        if (! $attribution || $attribution->agent_id !== Auth::id()) {
            abort(403);
        }
    }

    public function accepter(): void
    {
        $this->record->update(['statut' => 'enCours']);
        $this->record->refresh();
        $this->flashSuccess('Mission acceptée.');
    }

    public function refuser(): void
    {
        $this->validate(['motifRefus' => 'required|string|min:3']);

        $this->record->update(['statut' => 'enAttente', 'commentaire_agent' => $this->motifRefus]);
        $this->record->attribution?->delete();
        $this->showRefusModal = false;
        $this->flashWarning('Mission refusée.');
        $this->redirect(route('agent.missions.index'));
    }

    public function terminer(): void
    {
        $this->validate([
            'commentaireTerminer' => 'required|string|min:3',
            'photosApres.*' => 'nullable|image|max:5120',
        ]);

        $this->record->update([
            'statut' => 'terminer',
            'date_resolution' => now(),
            'commentaire_agent' => $this->commentaireTerminer,
        ]);

        foreach ($this->photosApres as $photo) {
            $path = $photo->store('signalements/interventions', 'public');
            SignalementPhoto::create([
                'signalement_id' => $this->record->id,
                'path' => $path,
                'type' => 'apres',
                'description' => 'Photo après intervention',
            ]);
        }

        $this->showTerminerModal = false;
        $this->flashSuccess('Intervention terminée avec succès.');
        $this->redirect(route('agent.missions.index'));
    }

    public function signalerDifficulte(): void
    {
        $this->validate([
            'difficulteType' => 'required|string',
            'difficulteDescription' => 'required|string|min:3',
        ]);

        SupportRequest::create([
            'agent_id' => Auth::id(),
            'type' => match ($this->difficulteType) {
                'renfort' => 'renfort',
                'materiel' => 'materiel',
                default => 'assistance_urgente',
            },
            'description' => $this->difficulteType . ': ' . $this->difficulteDescription,
            'statut' => 'en_attente',
        ]);

        $this->showDifficulteModal = false;
        $this->flashWarning('Difficulté signalée. Un administrateur va traiter votre demande.');
    }

    public function missionImpossible(): void
    {
        $this->validate(['motifImpossible' => 'required|string|min:3']);

        $this->record->update([
            'statut' => 'rejeter',
            'commentaire_agent' => 'Mission impossible: ' . $this->motifImpossible,
        ]);

        $this->showImpossibleModal = false;
        $this->flashWarning('Mission signalée comme impossible.');
        $this->redirect(route('agent.missions.index'));
    }

    public function render()
    {
        return view('livewire.agent.detail-mission');
    }
}

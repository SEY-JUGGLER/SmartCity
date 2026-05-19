<?php

namespace App\Livewire\Citoyen;

use App\Livewire\Concerns\HandlesFlashMessages;
use App\Models\Categorie;
use App\Models\Signalement;
use App\Models\SignalementPhoto;
use App\Models\Zone;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.citoyen')]
class CreerSignalement extends Component
{
    use HandlesFlashMessages;
    use WithFileUploads;

    public string $position = '';
    public ?float $latitude = null;
    public ?float $longitude = null;
    public ?int $categorie_id = null;
    public string $priorite = 'faible';
    public string $description = '';
    public ?int $zone_id = null;
    public array $photos = [];

    public function mount(): void
    {
        abort_unless(Auth::user()?->isCitoyen(), 403);
    }

    public function submit(): void
    {
        $this->validate([
            'position' => 'required|string|min:3',
            'categorie_id' => 'required|exists:categories,id',
            'priorite' => 'required|in:faible,moyenne,critique',
            'description' => 'required|string|min:10',
            'photos.*' => 'nullable|image|max:5120',
        ]);

        $signalement = Signalement::create([
            'position' => $this->position,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'description' => $this->description,
            'priorite' => $this->priorite,
            'statut' => 'enAttente',
            'dateSignalement' => now(),
            'user_id' => Auth::id(),
            'categorie_id' => $this->categorie_id,
            'zone_id' => $this->zone_id,
        ]);

        foreach ($this->photos as $photo) {
            $path = $photo->store('signalements/photos', 'public');
            SignalementPhoto::create([
                'signalement_id' => $signalement->id,
                'path' => $path,
                'type' => 'citoyen',
            ]);
        }

        $this->flashSuccess('Signalement #' . $signalement->id . ' créé avec succès.');
        $this->redirect(route('citoyen.signalements.index'));
    }

    public function render()
    {
        return view('livewire.citoyen.creer-signalement', [
            'categories' => Categorie::where('active', true)->orderBy('nom')->get(),
            'zones' => Zone::orderBy('nomZone')->get(),
        ]);
    }
}

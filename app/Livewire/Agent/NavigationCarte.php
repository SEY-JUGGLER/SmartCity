<?php

namespace App\Livewire\Agent;

use App\Models\Attribution;
use App\Models\Localisation;
use App\Livewire\Concerns\HandlesFlashMessages;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.agent')]
class NavigationCarte extends Component
{
    use HandlesFlashMessages;

    public float $lat = 0;
    public float $lng = 0;
    public bool $partageActif = false;

    public function mount(): void
    {
        abort_unless(Auth::user()?->isAgent(), 403);
        $last = Localisation::where('user_id', Auth::id())->latest()->first();
        if ($last) {
            $this->lat = (float) $last->latitude;
            $this->lng = (float) $last->longitude;
        }
    }

    public function sauvegarderPosition(float $lat, float $lng): void
    {
        Localisation::create([
            'user_id' => Auth::id(),
            'latitude' => $lat,
            'longitude' => $lng,
            'dateHeure' => now(),
        ]);
        $this->lat = $lat;
        $this->lng = $lng;
    }

    public function activerPartage(): void
    {
        $this->partageActif = true;
        $this->flashSuccess('Partage de position actif.');
    }

    public function desactiverPartage(): void
    {
        $this->partageActif = false;
        $this->flashWarning('Partage de position désactivé.');
    }

    public function getMissionsProperty(): array
    {
        return Attribution::with(['signalement.categorie'])
            ->where('agent_id', Auth::id())
            ->whereHas('signalement', fn ($q) => $q->whereIn('statut', ['enAttente', 'enCours']))
            ->get()
            ->map(fn ($a) => [
                'id' => $a->signalement_id,
                'position' => $a->signalement->position,
                'lat' => (float) ($a->signalement->latitude ?? 0),
                'lng' => (float) ($a->signalement->longitude ?? 0),
                'priorite' => $a->signalement->priorite,
                'categorie' => $a->signalement->categorie?->nom ?? '—',
            ])->toArray();
    }

    public function render()
    {
        return view('livewire.agent.navigation-carte', ['missions' => $this->missions]);
    }
}

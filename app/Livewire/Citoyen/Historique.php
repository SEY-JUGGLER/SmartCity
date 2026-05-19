<?php

namespace App\Livewire\Citoyen;

use App\Models\Signalement;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.citoyen')]
class Historique extends Component
{
    use WithPagination;

    public function mount(): void
    {
        abort_unless(Auth::user()?->isCitoyen(), 403);
    }

    public function render()
    {
        $signalements = Signalement::with(['categorie', 'attribution.agent', 'evaluation'])
            ->where('user_id', Auth::id())
            ->whereIn('statut', ['terminer', 'rejeter'])
            ->latest()
            ->paginate(15);

        return view('livewire.citoyen.historique', compact('signalements'));
    }
}

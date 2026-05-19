<?php

namespace App\Livewire\Citoyen;

use App\Models\Signalement;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.citoyen')]
class MesSignalements extends Component
{
    use WithPagination;

    public string $statutFilter = '';
    public string $prioriteFilter = '';

    public function mount(): void
    {
        abort_unless(Auth::user()?->isCitoyen(), 403);
    }

    public function render()
    {
        $signalements = Signalement::with(['categorie', 'attribution.agent', 'zone'])
            ->where('user_id', Auth::id())
            ->when($this->statutFilter, fn ($q) => $q->where('statut', $this->statutFilter))
            ->when($this->prioriteFilter, fn ($q) => $q->where('priorite', $this->prioriteFilter))
            ->latest()
            ->paginate(10);

        return view('livewire.citoyen.mes-signalements', compact('signalements'));
    }
}

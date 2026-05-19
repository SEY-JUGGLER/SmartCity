<?php

namespace App\Livewire\Agent;

use App\Models\Attribution;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.agent')]
class MesMissions extends Component
{
    use WithPagination;

    public string $statutFilter = '';

    public function mount(): void
    {
        abort_unless(Auth::user()?->isAgent(), 403);
    }

    public function updatingStatutFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $missions = Attribution::with(['signalement.categorie', 'signalement.zone', 'admin'])
            ->where('agent_id', Auth::id())
            ->when($this->statutFilter, fn ($q) => $q->whereHas(
                'signalement',
                fn ($q) => $q->where('statut', $this->statutFilter)
            ))
            ->orderByDesc('dateHeureAttribution')
            ->paginate(10);

        return view('livewire.agent.mes-missions', compact('missions'));
    }
}

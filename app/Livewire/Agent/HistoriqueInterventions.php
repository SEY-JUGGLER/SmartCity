<?php

namespace App\Livewire\Agent;

use App\Models\Attribution;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.agent')]
class HistoriqueInterventions extends Component
{
    use WithPagination;

    public string $statutFilter = '';

    public function mount(): void
    {
        abort_unless(Auth::user()?->isAgent(), 403);
    }

    public function render()
    {
        $items = Attribution::with(['signalement.categorie', 'signalement.evaluation'])
            ->where('agent_id', Auth::id())
            ->when($this->statutFilter, fn ($q) => $q->whereHas('signalement', fn ($q) => $q->where('statut', $this->statutFilter)))
            ->orderByDesc('dateHeureAttribution')
            ->paginate(15);

        return view('livewire.agent.historique-interventions', compact('items'));
    }
}

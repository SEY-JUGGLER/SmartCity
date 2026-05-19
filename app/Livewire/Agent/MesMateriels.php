<?php

namespace App\Livewire\Agent;

use App\Models\Materiel;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.agent')]
class MesMateriels extends Component
{
    use WithPagination;

    public string $statutFilter = '';

    public function mount(): void
    {
        abort_unless(Auth::user()?->isAgent(), 403);
    }

    public function render()
    {
        $materiels = Materiel::query()
            ->where(function ($q) {
                $q->where('agent_id', Auth::id())->orWhere('statut', 'disponible');
            })
            ->when($this->statutFilter, fn ($q) => $q->where('statut', $this->statutFilter))
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('livewire.agent.mes-materiels', compact('materiels'));
    }
}

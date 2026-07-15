<?php

namespace App\Livewire\Agent;

use App\Livewire\Concerns\HandlesFlashMessages;
use App\Models\Materiel;
use App\Models\SupportRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.agent')]
class SupportRequests extends Component
{
    use HandlesFlashMessages;
    use WithPagination;

    public bool $showForm = false;
    public string $type = '';
    public string $description = '';
    public ?int $materiel_id = null;

    public function mount(): void
    {
        abort_unless(Auth::user()?->isAgent(), 403);
    }

    public function create(): void
    {
        $rules = [
            'type' => 'required|in:renfort,materiel,panne_vehicule,assistance_urgente',
            'description' => 'required|string|min:5',
        ];

        if ($this->type === 'materiel') {
            $rules['materiel_id'] = 'required|exists:materiels,id';
        }

        $this->validate($rules);

        $data = [
            'agent_id' => Auth::id(),
            'type' => $this->type,
            'description' => $this->description,
            'statut' => 'en_attente',
        ];

        if ($this->type === 'materiel') {
            $data['description'] = 'Matériel #' . $this->materiel_id . ' : ' . $this->description;
        }

        SupportRequest::create($data);

        $this->reset(['type', 'description', 'materiel_id', 'showForm']);
        $this->flashSuccess('Demande envoyée.');
    }

    public function cancel(int $id): void
    {
        SupportRequest::where('agent_id', Auth::id())->where('id', $id)->where('statut', 'en_attente')->delete();
        $this->flashSuccess('Demande annulée.');
    }

    public function render()
    {
        $requests = SupportRequest::where('agent_id', Auth::id())->latest()->paginate(10);
        $materielsDisponibles = Materiel::where('statut', 'disponible')
            ->orWhere('agent_id', Auth::id())
            ->orderBy('nom')
            ->get();

        return view('livewire.agent.support-requests', compact('requests', 'materielsDisponibles'));
    }
}

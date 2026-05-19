<?php

namespace App\Livewire\Agent;

use App\Livewire\Concerns\HandlesFlashMessages;
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

    public function mount(): void
    {
        abort_unless(Auth::user()?->isAgent(), 403);
    }

    public function create(): void
    {
        $this->validate([
            'type' => 'required|in:renfort,materiel,panne_vehicule,assistance_urgente',
            'description' => 'required|string|min:5',
        ]);

        SupportRequest::create([
            'agent_id' => Auth::id(),
            'type' => $this->type,
            'description' => $this->description,
            'statut' => 'en_attente',
        ]);

        $this->reset(['type', 'description', 'showForm']);
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

        return view('livewire.agent.support-requests', compact('requests'));
    }
}

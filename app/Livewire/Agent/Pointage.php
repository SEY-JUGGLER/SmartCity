<?php

namespace App\Livewire\Agent;

use App\Livewire\Concerns\HandlesFlashMessages;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.agent')]
class Pointage extends Component
{
    use HandlesFlashMessages;

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless($user?->isAgent(), 403);

        if ($user->pointer && $user->heurePointage && $user->heurePointage->lte(now()->subHours(12))) {
            $user->update(['pointer' => false, 'heurePointage' => null, 'disponible' => false]);
        }
    }

    public function pointer(): void
    {
        Auth::user()->update(['pointer' => true, 'heurePointage' => now(), 'disponible' => true]);
        $this->flashSuccess('Pointage effectué. Vous êtes disponible pour les missions.');
    }

    public function activerDisponibilite(): void
    {
        Auth::user()->update(['disponible' => true]);
        $this->flashSuccess('Disponibilité activée.');
    }

    public function desactiverDisponibilite(): void
    {
        Auth::user()->update(['disponible' => false]);
        $this->flashWarning('Disponibilité désactivée.');
    }

    public function signalerAbsence(): void
    {
        Auth::user()->update(['disponible' => false, 'pointer' => false, 'heurePointage' => null]);
        $this->flashWarning('Absence signalée.');
    }

    public function render()
    {
        return view('livewire.agent.pointage', ['user' => Auth::user()]);
    }
}

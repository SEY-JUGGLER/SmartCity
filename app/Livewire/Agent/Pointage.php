<?php

namespace App\Livewire\Agent;

use App\Livewire\Concerns\HandlesFlashMessages;
use App\Models\PointageHistorique;
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
        $user = Auth::user();
        $user->update(['pointer' => true, 'heurePointage' => now(), 'disponible' => true]);
        PointageHistorique::create([
            'user_id' => $user->id,
            'action' => 'pointer',
            'pointer' => true,
            'disponible' => true,
            'heure_action' => now(),
        ]);
        $this->flashSuccess('Pointage effectué. Vous êtes disponible pour les missions.');
    }

    public function activerDisponibilite(): void
    {
        $user = Auth::user();
        $user->update(['disponible' => true]);
        PointageHistorique::create([
            'user_id' => $user->id,
            'action' => 'activer_disponibilite',
            'pointer' => $user->pointer,
            'disponible' => true,
            'heure_action' => now(),
        ]);
        $this->flashSuccess('Disponibilité activée.');
    }

    public function desactiverDisponibilite(): void
    {
        $user = Auth::user();
        $user->update(['disponible' => false]);
        PointageHistorique::create([
            'user_id' => $user->id,
            'action' => 'desactiver_disponibilite',
            'pointer' => $user->pointer,
            'disponible' => false,
            'heure_action' => now(),
        ]);
        $this->flashWarning('Disponibilité désactivée.');
    }

    public function signalerAbsence(): void
    {
        $user = Auth::user();
        $user->update(['disponible' => false, 'pointer' => false, 'heurePointage' => null]);
        PointageHistorique::create([
            'user_id' => $user->id,
            'action' => 'absence',
            'pointer' => false,
            'disponible' => false,
            'heure_action' => now(),
        ]);
        $this->flashWarning('Absence signalée.');
    }

    public function render()
    {
        $historique = PointageHistorique::where('user_id', Auth::id())
            ->orderBy('heure_action', 'desc')
            ->limit(20)
            ->get();

        return view('livewire.agent.pointage', [
            'user' => Auth::user(),
            'historique' => $historique,
        ]);
    }
}

<?php

namespace App\Livewire\Citoyen;

use App\Models\User;
use App\Models\Signalement;
use App\Models\Evaluation;
use App\Services\ClassificationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.citoyen')]
class ClassementEngagement extends Component
{
    public string $tri = 'signalements';

    public function mount(): void
    {
        abort_unless(Auth::user()?->isCitoyen(), 403);
    }

    public function render()
    {
        $citoyens = User::where('role', 'CITOYEN')
            ->get()
            ->map(function ($user) {
                $signalementsCount = Signalement::where('user_id', $user->id)->count();
                $evaluationsCount  = Evaluation::where('user_id', $user->id)->count();
                $terminesCount     = Signalement::where('user_id', $user->id)->where('statut', 'terminer')->count();
                $classification    = ClassificationService::classifierCitoyen($user->id);

                return (object) [
                    'id'             => $user->id,
                    'nom'            => $user->prenom . ' ' . $user->name,
                    'email'          => $user->email,
                    'photo'          => $user->photoProfi,
                    'signalements'   => $signalementsCount,
                    'evaluations'    => $evaluationsCount,
                    'termines'       => $terminesCount,
                    'engagement'     => $signalementsCount + $evaluationsCount,
                    'classification' => $classification,
                ];
            })
            ->sortByDesc($this->tri === 'signalements' ? 'signalements' : 'evaluations')
            ->values();

        $monRang = $citoyens->search(fn ($c) => $c->id === Auth::id());

        return view('livewire.citoyen.classement-engagement', compact('citoyens', 'monRang'));
    }
}

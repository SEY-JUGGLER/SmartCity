<?php

namespace App\Livewire\Agent;

use App\Models\Attribution;
use App\Models\PointageHistorique;
use App\Models\Signalement;
use App\Models\SupportRequest;
use App\Models\Materiel;
use App\Services\ClassificationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.agent')]

class Dashboard extends Component
{
    public bool $disponible = true;
    public bool $pointe = false;

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless($user && $user->isAgent(), 403);

        if ($user->pointer && $user->heurePointage && $user->heurePointage->lte(now()->subHours(12))) {
            $user->update(['pointer' => false, 'heurePointage' => null, 'disponible' => false]);
            $this->pointe = false;
            $this->disponible = false;
        } else {
            $this->disponible = $user->disponible ?? true;
            $this->pointe = $user->pointer ?? false;
        }
    }

    public function toggleDisponibilite(): void
    {
        $user = Auth::user();
        $this->disponible = !$this->disponible;
        $user->update(['disponible' => $this->disponible]);
        PointageHistorique::create([
            'user_id' => $user->id,
            'action' => $this->disponible ? 'activer_disponibilite' : 'desactiver_disponibilite',
            'pointer' => $user->pointer,
            'disponible' => $this->disponible,
            'heure_action' => now(),
        ]);
    }

    public function pointer(): void
    {
        $user = Auth::user();
        $user->update(['pointer' => true, 'heurePointage' => now(), 'disponible' => true]);
        $this->pointe = true;
        $this->disponible = true;
        PointageHistorique::create([
            'user_id' => $user->id,
            'action' => 'pointer',
            'pointer' => true,
            'disponible' => true,
            'heure_action' => now(),
        ]);
    }

    public function logout(): void
    {
        Auth::guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();
        $this->redirect('/');
    }

    #[Computed]
    public function stats(): array
    {
        $agentId = Auth::id();
        $signalementsIds = Attribution::where('agent_id', $agentId)->pluck('signalement_id');

        return [
            'missionsEnCours'   => Signalement::whereIn('id', $signalementsIds)->where('statut', 'enCours')->count(),
            'missionsTerminees' => Signalement::whereIn('id', $signalementsIds)->where('statut', 'terminer')->count(),
            'totalMissions'     => $signalementsIds->count(),
            'supportEnAttente'  => SupportRequest::where('agent_id', $agentId)->where('statut', 'en_attente')->count(),
            'materielsAttribues'=> Materiel::where('agent_id', $agentId)->count(),
        ];
    }

    #[Computed]
    public function classification(): array
    {
        return ClassificationService::classifierAgent(Auth::id());
    }

    #[Computed]
    public function interventionsToday(): array
    {
        $agentId = Auth::id();
        $signalementIds = Attribution::where('agent_id', $agentId)->pluck('signalement_id');

        return Signalement::whereIn('id', $signalementIds)
            ->where(function ($q) {
                $q->whereDate('created_at', today())
                  ->orWhereDate('date_resolution', today());
            })
            ->with('categorie', 'zone')
            ->latest()
            ->take(5)
            ->get()
            ->toArray();
    }

    #[Computed]
    public function chartData(): array
    {
        $agentId = Auth::id();
        $signalementsIds = Attribution::where('agent_id', $agentId)->pluck('signalement_id');

        $data = [];
        foreach (range(6, 0) as $d) {
            $date = now()->subMonths($d);
            $data['labels'][] = $date->format('M');
            $data['terminees'][] = Signalement::whereIn('id', $signalementsIds)
                ->where('statut', 'terminer')
                ->whereMonth('date_resolution', $date->month)
                ->whereYear('date_resolution', $date->year)
                ->count();
            $data['attribuees'][] = Attribution::where('agent_id', $agentId)
                ->whereMonth('dateHeureAttribution', $date->month)
                ->whereYear('dateHeureAttribution', $date->year)
                ->count();
        }
        return $data;
    }

    public function render()
    {
        return view('livewire.agent.dashboard');
    }
}

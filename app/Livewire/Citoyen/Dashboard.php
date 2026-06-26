<?php

namespace App\Livewire\Citoyen;

use App\Models\Evaluation;
use App\Models\Signalement;
use App\Services\ClassificationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.citoyen')]

class Dashboard extends Component
{
    public function mount(): void
    {
        abort_unless(Auth::user()?->isCitoyen(), 403);
    }

    #[Computed]
    public function stats(): array
    {
        $userId = Auth::id();

        return [
            'total'      => Signalement::where('user_id', $userId)->count(),
            'enAttente'  => Signalement::where('user_id', $userId)->where('statut', 'enAttente')->count(),
            'enCours'    => Signalement::where('user_id', $userId)->where('statut', 'enCours')->count(),
            'termines'   => Signalement::where('user_id', $userId)->where('statut', 'terminer')->count(),
            'rejetes'    => Signalement::where('user_id', $userId)->where('statut', 'rejeter')->count(),
            'evaluations'=> Evaluation::where('user_id', $userId)->count(),
        ];
    }

    #[Computed]
    public function classification(): array
    {
        return ClassificationService::classifierCitoyen(Auth::id());
    }

    #[Computed]
    public function recentSignalements(): array
    {
        return Signalement::where('user_id', Auth::id())
            ->with('categorie', 'zone')
            ->latest()
            ->take(5)
            ->get()
            ->toArray();
    }

    #[Computed]
    public function chartData(): array
    {
        $userId = Auth::id();
        $data = [];

        foreach (range(6, 0) as $d) {
            $date = now()->subMonths($d);
            $data['labels'][] = $date->format('M');
            $data['signalements'][] = Signalement::where('user_id', $userId)
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            $data['termines'][] = Signalement::where('user_id', $userId)
                ->where('statut', 'terminer')
                ->whereMonth('date_resolution', $date->month)
                ->whereYear('date_resolution', $date->year)
                ->count();
        }

        return $data;
    }

    public function render()
    {
        return view('livewire.citoyen.dashboard');
    }
}

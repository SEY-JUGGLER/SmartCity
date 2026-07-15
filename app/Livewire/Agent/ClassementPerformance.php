<?php

namespace App\Livewire\Agent;

use App\Models\User;
use App\Models\Attribution;
use App\Models\Evaluation;
use App\Services\ClassificationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.agent')]
class ClassementPerformance extends Component
{
    public string $tri = 'missions';

    public function mount(): void
    {
        abort_unless(Auth::user()?->isAgent(), 403);
    }

    public function render()
    {
        $agents = User::where('role', 'AGENT')
            ->withCount(['attributionsAgent as missions_terminees' => function ($q) {
                $q->whereHas('signalement', fn ($q) => $q->where('statut', 'terminer'));
            }])
            ->withCount(['attributionsAgent as total_missions'])
            ->get()
            ->map(function ($agent) {
                $evals = Evaluation::whereHas(
                    'signalement.attribution',
                    fn ($q) => $q->where('agent_id', $agent->id)
                )->get();
                $avgNote = $evals->count() > 0
                    ? $evals->avg(fn ($e) => Evaluation::NOTE_SCORES[$e->note] ?? 0)
                    : 0;

                $classification = ClassificationService::classifierAgent($agent->id);

                return (object) [
                    'id'                 => $agent->id,
                    'nom'                => $agent->prenom . ' ' . $agent->name,
                    'email'              => $agent->email,
                    'photo'              => $agent->photoProfi,
                    'missions_terminees' => (int) $agent->missions_terminees,
                    'total_missions'     => (int) $agent->total_missions,
                    'note_moyenne'       => round((float) $avgNote, 1),
                    'classification'     => $classification,
                ];
            })
            ->sortByDesc($this->tri === 'missions' ? 'missions_terminees' : 'note_moyenne')
            ->values();

        $monRang = $agents->search(fn ($a) => $a->id === Auth::id());

        return view('livewire.agent.classement-performance', compact('agents', 'monRang'));
    }
}

<?php

namespace App\Livewire\Agent;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.agent')]
class Notifications extends Component
{
    use WithPagination;

    public function mount(): void
    {
        abort_unless(Auth::user()?->isAgent(), 403);
    }

    public function marquerLue(string $id): void
    {
        Auth::user()->notifications()->where('id', $id)->first()?->markAsRead();
    }

    public function marquerToutesLues(): void
    {
        Auth::user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(20);

        return view('livewire.agent.notifications', compact('notifications'));
    }
}

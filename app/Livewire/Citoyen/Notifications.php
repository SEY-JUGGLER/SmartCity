<?php

namespace App\Livewire\Citoyen;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.citoyen')]
class Notifications extends Component
{
    use WithPagination;

    public function mount(): void
    {
        abort_unless(Auth::user()?->isCitoyen(), 403);
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

        return view('livewire.citoyen.notifications', compact('notifications'));
    }
}

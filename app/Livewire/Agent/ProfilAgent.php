<?php

namespace App\Livewire\Agent;

use App\Livewire\Concerns\HandlesFlashMessages;
use App\Models\NotificationPreference;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.agent')]
class ProfilAgent extends Component
{
    use HandlesFlashMessages;
    use WithFileUploads;

    public string $prenom = '';
    public string $name = '';
    public string $email = '';
    public ?int $age = null;
    public string $localite = '';
    public string $telephone = '';
    public $photoProfi = null;
    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';
    public bool $notification_systeme = true;
    public bool $notification_email = true;
    public bool $notification_push = false;

    public function mount(): void
    {
        abort_unless(Auth::user()?->isAgent(), 403);
        $user = Auth::user();
        $prefs = $user->notificationPreferences;
        $this->fill([
            'prenom' => $user->prenom ?? '',
            'name' => $user->name ?? '',
            'email' => $user->email,
            'age' => $user->age,
            'localite' => $user->localite ?? '',
            'telephone' => $user->telephone ?? '',
            'notification_systeme' => $prefs?->notification_systeme ?? true,
            'notification_email' => $prefs?->notification_email ?? true,
            'notification_push' => $prefs?->notification_push ?? false,
        ]);
    }

    public function save(): void
    {
        $rules = [
            'prenom' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'telephone' => 'nullable|string',
        ];
        if ($this->new_password) {
            $rules['current_password'] = 'required|current_password';
            $rules['new_password'] = 'required|min:8|confirmed';
        }
        $this->validate($rules);

        $user = Auth::user();
        $data = $this->only(['prenom', 'name', 'email', 'age', 'localite', 'telephone']);
        if ($this->photoProfi) {
            $data['photoProfi'] = $this->photoProfi->store('profils', 'public');
        }
        if ($this->new_password) {
            $data['password'] = Hash::make($this->new_password);
        }
        $user->update($data);

        NotificationPreference::updateOrCreate(
            ['user_id' => $user->id],
            [
                'notification_systeme' => $this->notification_systeme,
                'notification_email' => $this->notification_email,
                'notification_push' => $this->notification_push,
            ]
        );

        $this->flashSuccess('Profil mis à jour.');
    }

    public function render()
    {
        return view('livewire.agent.profil-agent');
    }
}

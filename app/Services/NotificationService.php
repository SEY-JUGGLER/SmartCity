<?php

namespace App\Services;

use App\Models\Signalement;
use App\Models\User;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use App\Notifications\SignalementNotification;

class NotificationService
{
    public function notifySignalementCreated(Signalement $signalement): void
    {
        $citoyen = $signalement->citoyen;
        if ($citoyen) {
            $citoyen->notify(new SignalementNotification(
                title: 'Signalement #' . $signalement->id . ' créé',
                body: 'Votre signalement a été soumis avec succès.',
                type: 'signalement_create',
                signalement_id: $signalement->id,
            ));
        }

        $admins = User::where('role', 'ADMIN')->get();
        NotificationFacade::send($admins, new SignalementNotification(
            title: 'Nouveau signalement #' . $signalement->id,
            body: $signalement->description,
            type: 'signalement_create',
            signalement_id: $signalement->id,
        ));
    }

    public function notifySignalementAttributed(Signalement $signalement): void
    {
        $agent = $signalement->attribution?->agent;
        if ($agent) {
            $agent->notify(new SignalementNotification(
                title: 'Mission #' . $signalement->id . ' assignée',
                body: 'Un nouveau signalement vous a été attribué : ' . $signalement->description,
                type: 'signalement_attributed',
                signalement_id: $signalement->id,
            ));
        }
    }

    public function notifyStatusChanged(Signalement $signalement): void
    {
        $citoyen = $signalement->citoyen;
        if ($citoyen) {
            $label = match ($signalement->statut) {
                'enCours' => 'pris en charge',
                'terminer' => 'résolu',
                'rejeter' => 'refusé',
                default => 'mis à jour',
            };
            $citoyen->notify(new SignalementNotification(
                title: 'Signalement #' . $signalement->id . ' ' . $label,
                body: 'Le statut de votre signalement a changé : ' . $signalement->statut,
                type: 'signalement_status',
                signalement_id: $signalement->id,
            ));
        }
    }
}

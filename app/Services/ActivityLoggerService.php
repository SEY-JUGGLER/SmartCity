<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

class ActivityLoggerService
{
    public function log(
        string $action,
        ?string $description = null,
        ?string $targetType = null,
        ?int $targetId = null,
        ?array $oldValues = null,
        ?array $newValues = null,
    ): ActivityLog {
        $user = auth()->user();

        return ActivityLog::create([
            'user_id'     => $user?->id,
            'user_name'   => $user ? ($user->prenom . ' ' . $user->name) : 'Système',
            'user_role'   => $user?->role ?? 'SYSTEM',
            'action'      => $action,
            'target_type' => $targetType,
            'target_id'   => $targetId,
            'description' => $description,
            'old_values'  => $oldValues,
            'new_values'  => $newValues,
            'ip_address'  => request()->ip(),
        ]);
    }

    public function logSignalementCreated(Model $signalement): void
    {
        $user = $signalement->citoyen;
        $this->log(
            action: 'signalement.created',
            description: "Signalement #{$signalement->id} créé par {$user?->prenom} {$user?->name}",
            targetType: 'signalement',
            targetId: $signalement->id,
            newValues: $signalement->toArray(),
        );
    }

    public function logSignalementAttributed(Model $signalement): void
    {
        $agent = $signalement->attribution?->agent;
        $adminName = auth()->user()?->prenom . ' ' . auth()->user()?->name;
        $this->log(
            action: 'signalement.attributed',
            description: "Signalement #{$signalement->id} attribué à {$agent?->prenom} {$agent?->name} par {$adminName}",
            targetType: 'signalement',
            targetId: $signalement->id,
            newValues: ['agent_id' => $agent?->id],
        );
    }

    public function logSignalementStatusChanged(Model $signalement, string $oldStatus, string $newStatus): void
    {
        $adminName = auth()->user()?->prenom . ' ' . auth()->user()?->name;
        $this->log(
            action: 'signalement.status_changed',
            description: "Signalement #{$signalement->id} : {$oldStatus} → {$newStatus} par {$adminName}",
            targetType: 'signalement',
            targetId: $signalement->id,
            oldValues: ['statut' => $oldStatus],
            newValues: ['statut' => $newStatus],
        );
    }

    public function logSupportValidated(Model $support): void
    {
        $adminName = auth()->user()?->prenom . ' ' . auth()->user()?->name;
        $this->log(
            action: 'support.valide',
            description: "Demande support #{$support->id} validée par {$adminName}",
            targetType: 'support',
            targetId: $support->id,
        );
    }

    public function logSupportRefused(Model $support): void
    {
        $adminName = auth()->user()?->prenom . ' ' . auth()->user()?->name;
        $this->log(
            action: 'support.refuse',
            description: "Demande support #{$support->id} refusée par {$adminName}",
            targetType: 'support',
            targetId: $support->id,
        );
    }

    public function logRapportGenerated(Model $rapport): void
    {
        $adminName = auth()->user()?->prenom . ' ' . auth()->user()?->name;
        $this->log(
            action: 'rapport.generated',
            description: "Rapport #{$rapport->id} généré par {$adminName}",
            targetType: 'rapport',
            targetId: $rapport->id,
        );
    }

    public function logUserAction(string $action, Model $target, ?string $description = null): void
    {
        $this->log(
            action: $action,
            description: $description ?? "{$action} sur {$target->getMorphClass()} #{$target->id}",
            targetType: $target->getMorphClass(),
            targetId: $target->id,
        );
    }
}

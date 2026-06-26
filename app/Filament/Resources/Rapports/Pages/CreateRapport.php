<?php

namespace App\Filament\Resources\Rapports\Pages;

use App\Filament\Resources\Rapports\RapportResource;
use App\Models\Rapport;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateRapport extends CreateRecord
{
    protected static string $resource = RapportResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        if (empty($data['nbrSignalement']) || $data['nbrSignalement'] == 0) {
            $stats = Rapport::calculerStats($data['date_debut'] ?? null, $data['date_fin'] ?? null);
            $data  = array_merge($data, $stats);
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        Notification::make()
            ->title('Rapport cree avec succes')
            ->body('Rapport #' . $this->getRecord()->id . ' du ' . ($this->getRecord()->dateGeneration?->format('d/m/Y') ?? '—'))
            ->success()
            ->send();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

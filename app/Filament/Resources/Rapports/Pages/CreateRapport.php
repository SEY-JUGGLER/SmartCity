<?php

namespace App\Filament\Resources\Rapports\Pages;

use App\Filament\Resources\Rapports\RapportResource;
use App\Models\Rapport;
use Filament\Resources\Pages\CreateRecord;

class CreateRapport extends CreateRecord
{
    protected static string $resource = RapportResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        // Auto-calculer les stats si elles sont à zéro (formulaire non pré-rempli)
        if (empty($data['nbrSignalement']) || $data['nbrSignalement'] == 0) {
            $stats = Rapport::calculerStats($data['date_debut'] ?? null, $data['date_fin'] ?? null);
            $data  = array_merge($data, $stats);
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }
}

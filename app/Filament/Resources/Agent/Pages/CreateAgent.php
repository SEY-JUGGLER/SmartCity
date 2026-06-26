<?php

namespace App\Filament\Resources\Agent\Pages;

use App\Filament\Resources\Agent\AgentResource;
use App\Models\Localisation;
use Filament\Resources\Pages\CreateRecord;

class CreateAgent extends CreateRecord
{
    protected static string $resource = AgentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['role'] = 'AGENT';

        return $data;
    }

    protected function afterCreate(): void
    {
        if ($this->record->zone_id && $this->record->zone) {
            $zone = $this->record->zone;
            if ($zone->latitude && $zone->longitude) {
                Localisation::create([
                    'user_id'   => $this->record->id,
                    'latitude'  => $zone->latitude,
                    'longitude' => $zone->longitude,
                    'dateHeure' => now(),
                ]);
            }
        }
    }
}
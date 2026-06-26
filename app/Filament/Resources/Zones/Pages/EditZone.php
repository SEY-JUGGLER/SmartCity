<?php

namespace App\Filament\Resources\Zones\Pages;

use App\Filament\Resources\Zones\ZoneResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditZone extends EditRecord
{
    protected static string $resource = ZoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    public function setCoordinates(string $lat, string $lng): void
    {
        $this->data['latitude']  = $lat;
        $this->data['longitude'] = $lng;
    }

    protected function afterFill(): void
    {
        $lat = $this->data['latitude'] ?? null;
        $lng = $this->data['longitude'] ?? null;
        if ($lat && $lng) {
            $this->dispatch('zone-map-init', lat: (string) $lat, lng: (string) $lng);
        }
    }
}

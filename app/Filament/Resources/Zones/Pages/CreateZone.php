<?php

namespace App\Filament\Resources\Zones\Pages;

use App\Filament\Resources\Zones\ZoneResource;
use Filament\Resources\Pages\CreateRecord;

class CreateZone extends CreateRecord
{
    protected static string $resource = ZoneResource::class;

    public function setCoordinates(string $lat, string $lng): void
    {
        $this->data['latitude']  = $lat;
        $this->data['longitude'] = $lng;
    }
}

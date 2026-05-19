<?php

namespace App\Filament\Resources\Attributions\Pages;

use App\Filament\Resources\Attributions\AttributionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAttribution extends ViewRecord
{
    protected static string $resource = AttributionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

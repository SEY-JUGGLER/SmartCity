<?php

namespace App\Filament\Resources\Attributions\Pages;

use App\Filament\Resources\Attributions\AttributionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAttributions extends ListRecords
{
    protected static string $resource = AttributionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

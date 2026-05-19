<?php

namespace App\Filament\Resources\Attributions\Pages;

use App\Filament\Resources\Attributions\AttributionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAttribution extends EditRecord
{
    protected static string $resource = AttributionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

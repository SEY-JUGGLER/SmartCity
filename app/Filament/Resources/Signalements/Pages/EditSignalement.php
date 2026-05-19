<?php

namespace App\Filament\Resources\Signalements\Pages;

use App\Filament\Resources\Signalements\SignalementResource;
use Filament\Resources\Pages\EditRecord;

class EditSignalement extends EditRecord
{
    protected static string $resource = SignalementResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
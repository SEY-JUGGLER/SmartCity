<?php

namespace App\Filament\Resources\Agent\Pages;

use App\Filament\Resources\Agent\AgentResource;
use Filament\Resources\Pages\EditRecord;

class EditAgent extends EditRecord
{
    protected static string $resource = AgentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
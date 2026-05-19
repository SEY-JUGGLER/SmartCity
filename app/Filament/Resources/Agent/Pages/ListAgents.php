<?php

namespace App\Filament\Resources\Agent\Pages;

use App\Filament\Resources\Agent\AgentResource;
use Filament\Resources\Pages\ListRecords;

class ListAgents extends ListRecords
{
    protected static string $resource = AgentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
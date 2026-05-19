<?php

namespace App\Filament\Resources\Admin\Pages;

use App\Filament\Resources\Admin\AdminResource;
use Filament\Resources\Pages\ListRecords;

class ListAdmins extends ListRecords
{
    protected static string $resource = AdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
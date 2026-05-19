<?php

namespace App\Filament\Resources\Materiel\Pages;

use App\Filament\Resources\Materiel\MaterielResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMateriels extends ListRecords
{
    protected static string $resource = MaterielResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Nouveau matériel'),
        ];
    }
}

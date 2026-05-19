<?php

namespace App\Filament\Resources\Categorie\Pages;

use App\Filament\Resources\Categorie\CategorieResource;
use Filament\Resources\Pages\ListRecords;

class ListCategories extends ListRecords
{
    protected static string $resource = CategorieResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
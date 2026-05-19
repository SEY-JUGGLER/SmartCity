<?php

namespace App\Filament\Resources\Categorie\Pages;

use App\Filament\Resources\Categorie\CategorieResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCategorie extends CreateRecord
{
    protected static string $resource = CategorieResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
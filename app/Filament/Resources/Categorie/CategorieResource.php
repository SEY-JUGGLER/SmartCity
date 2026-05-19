<?php

namespace App\Filament\Resources\Categorie;

use App\Filament\Resources\Categorie\Pages\CreateCategorie;
use App\Filament\Resources\Categorie\Pages\EditCategorie;
use App\Filament\Resources\Categorie\Pages\ListCategories;
use App\Filament\Resources\Categorie\Schemas\CategorieForm;
use App\Models\Categorie;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;
use BackedEnum;

class CategorieResource extends Resource
{
    protected static ?string $model = Categorie::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static UnitEnum|string|null $navigationGroup = 'Signalements';
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Catégories';

    public static function form(Schema $schema): Schema
    {
        return CategorieForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return \App\Filament\Resources\Categorie\Tables\CategoriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListCategories::route('/'),
            'create' => CreateCategorie::route('/create'),
            'edit'   => EditCategorie::route('/{record}/edit'),
        ];
    }
}
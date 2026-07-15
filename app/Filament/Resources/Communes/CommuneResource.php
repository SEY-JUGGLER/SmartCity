<?php

namespace App\Filament\Resources\Communes;

use App\Filament\Resources\Communes\Pages\CreateCommune;
use App\Filament\Resources\Communes\Pages\EditCommune;
use App\Filament\Resources\Communes\Pages\ListCommunes;
use App\Filament\Resources\Communes\Pages\ViewCommune;
use App\Filament\Resources\Communes\RelationManagers\ZonesRelationManager;
use App\Filament\Resources\Communes\Schemas\CommuneForm;
use App\Filament\Resources\Communes\Schemas\CommuneInfolist;
use App\Filament\Resources\Communes\Tables\CommunesTable;
use App\Models\Commune;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class CommuneResource extends Resource
{
    protected static ?string $model = Commune::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-building-office';
    protected static UnitEnum|string|null $navigationGroup = 'Zones & Missions';
    protected static ?string $navigationLabel = 'Communes';
    protected static ?int $navigationSort = 0;

    public static function form(Schema $schema): Schema
    {
        return CommuneForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CommuneInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CommunesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ZonesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCommunes::route('/'),
            'create' => CreateCommune::route('/create'),
            'view' => ViewCommune::route('/{record}'),
            'edit' => EditCommune::route('/{record}/edit'),
        ];
    }
}

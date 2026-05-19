<?php

namespace App\Filament\Resources\Rapports;

use App\Filament\Resources\Rapports\Pages\CreateRapport;
use App\Filament\Resources\Rapports\Pages\EditRapport;
use App\Filament\Resources\Rapports\Pages\ListRapports;
use App\Filament\Resources\Rapports\Pages\ViewRapport;
use App\Filament\Resources\Rapports\Schemas\RapportForm;
use App\Filament\Resources\Rapports\Schemas\RapportInfolist;
use App\Filament\Resources\Rapports\Tables\RapportsTable;
use App\Models\Rapport;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RapportResource extends Resource
{
    protected static ?string $model = Rapport::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static UnitEnum|string|null $navigationGroup = 'Rapports';
    protected static ?string $navigationLabel = 'Rapports';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return RapportForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RapportInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RapportsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRapports::route('/'),
            'create' => CreateRapport::route('/create'),
            'view' => ViewRapport::route('/{record}'),
            'edit' => EditRapport::route('/{record}/edit'),
        ];
    }
}

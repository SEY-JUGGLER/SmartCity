<?php

namespace App\Filament\Resources\Attributions;

use App\Filament\Resources\Attributions\Pages\CreateAttribution;
use App\Filament\Resources\Attributions\Pages\EditAttribution;
use App\Filament\Resources\Attributions\Pages\ListAttributions;
use App\Filament\Resources\Attributions\Pages\ViewAttribution;
use App\Filament\Resources\Attributions\Schemas\AttributionForm;
use App\Filament\Resources\Attributions\Schemas\AttributionInfolist;
use App\Filament\Resources\Attributions\Tables\AttributionsTable;
use App\Models\Attribution;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AttributionResource extends Resource
{
    protected static ?string $model = Attribution::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static UnitEnum|string|null $navigationGroup = 'Zones & Missions';
    protected static ?string $navigationLabel = 'Attributions';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return AttributionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AttributionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttributionsTable::configure($table);
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
            'index' => ListAttributions::route('/'),
            'create' => CreateAttribution::route('/create'),
            'view' => ViewAttribution::route('/{record}'),
            'edit' => EditAttribution::route('/{record}/edit'),
        ];
    }
}

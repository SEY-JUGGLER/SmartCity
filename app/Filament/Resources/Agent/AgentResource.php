<?php

namespace App\Filament\Resources\Agent;

use App\Filament\Resources\Agent\Pages\CreateAgent;
use App\Filament\Resources\Agent\Pages\EditAgent;
use App\Filament\Resources\Agent\Pages\ListAgents;
use App\Filament\Resources\Agent\Pages\ViewAgent;
use App\Filament\Resources\Agent\Schemas\AgentForm;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;
use BackedEnum;

class AgentResource extends Resource
{
    protected static ?string $model = User::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-user-group';

    protected static UnitEnum|string|null $navigationGroup = 'Utilisateurs';
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Agents';

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('role', 'AGENT');
    }

    public static function form(Schema $schema): Schema
    {
        return AgentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return \App\Filament\Resources\Agent\Tables\AgentsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListAgents::route('/'),
            'create' => CreateAgent::route('/create'),
            'view'   => ViewAgent::route('/{record}'),
            'edit'   => EditAgent::route('/{record}/edit'),
        ];
    }
}
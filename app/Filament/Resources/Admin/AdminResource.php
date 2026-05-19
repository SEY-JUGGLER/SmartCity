<?php

namespace App\Filament\Resources\Admin;

use App\Filament\Resources\Admin\Pages\CreateAdmin;
use App\Filament\Resources\Admin\Pages\EditAdmin;
use App\Filament\Resources\Admin\Pages\ListAdmins;
use App\Filament\Resources\Admin\Schemas\AdminForm;
use App\Filament\Resources\Admin\Tables\AdminsTable;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;
use BackedEnum;

class AdminResource extends Resource
{
    protected static ?string $model = User::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-shield-check';

    protected static UnitEnum|string|null $navigationGroup = 'Utilisateurs';
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Administrateurs';

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('role', 'ADMIN');
    }

    public static function form(Schema $schema): Schema
    {
        return AdminForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return \App\Filament\Resources\Admin\Tables\AdminsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListAdmins::route('/'),
            'create' => CreateAdmin::route('/create'),
            'edit'   => EditAdmin::route('/{record}/edit'),
        ];
    }
}
<?php

namespace App\Filament\Resources\Pointage;

use App\Filament\Resources\Pointage\Pages\ListPointage;
use App\Filament\Resources\Pointage\Pages\ViewPointage;
use App\Filament\Resources\Pointage\Components\PointageActions;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use UnitEnum;
use BackedEnum;

class PointageResource extends Resource
{
    protected static ?string $model = User::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-clock';

    protected static UnitEnum|string|null $navigationGroup = 'Utilisateurs';

    protected static ?string $navigationLabel = 'Pointage';

    protected static ?int $navigationSort = 4;

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->where('role', 'AGENT')
            ->orderByDesc('heurePointage');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('prenom')
                    ->searchable()
                    ->sortable()
                    ->label('Prénom'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Nom'),
                TextColumn::make('email')
                    ->searchable()
                    ->label('Email'),
                TextColumn::make('pointer')
                    ->label('Pointé')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => $state ? 'Pointé' : 'Absent'),
                TextColumn::make('disponible')
                    ->label('Disponible')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'warning')
                    ->formatStateUsing(fn ($state) => $state ? 'Disponible' : 'Indisponible'),
                TextColumn::make('heurePointage')
                    ->label('Heure de pointage')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->placeholder('—'),
                TextColumn::make('zone.nomZone')
                    ->label('Zone')
                    ->badge()
                    ->color('info')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('pointer')
                    ->label('État de pointage')
                    ->options([
                        '1' => 'Pointé',
                        '0' => 'Non pointé',
                    ]),
                SelectFilter::make('disponible')
                    ->label('Disponibilité')
                    ->options([
                        '1' => 'Disponible',
                        '0' => 'Indisponible',
                    ]),
                Filter::make('pointe_aujourd_hui')
                    ->label("Pointé aujourd'hui")
                    ->query(fn ($query) => $query->whereDate('heurePointage', today())),
            ])
            ->recordActions([
                PointageActions::make(),
            ])
            ->toolbarActions([
                PointageActions::bulkPointageActions(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPointage::route('/'),
            'view'  => ViewPointage::route('/{record}'),
        ];
    }
}

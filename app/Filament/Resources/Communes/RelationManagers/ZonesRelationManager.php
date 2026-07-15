<?php

namespace App\Filament\Resources\Communes\RelationManagers;

use App\Filament\Resources\Zones\ZoneResource;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ZonesRelationManager extends RelationManager
{
    protected static string $relationship = 'zones';

    protected static ?string $title = 'Zones';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Informations de la zone')
                ->schema([
                    TextInput::make('nomZone')
                        ->label('Nom de la zone')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('superficie')
                        ->label('Superficie (km²)')
                        ->numeric()
                        ->step(0.01)
                        ->minValue(0),
                    TextInput::make('nombreHabitant')
                        ->label("Nombre d'habitants")
                        ->numeric()
                        ->minValue(0)
                        ->default(0),
                ])->columns(2),
            Section::make('Coordonnées géographiques')
                ->schema([
                    TextInput::make('latitude')
                        ->label('Latitude')
                        ->numeric()
                        ->step(0.0001),
                    TextInput::make('longitude')
                        ->label('Longitude')
                        ->numeric()
                        ->step(0.0001),
                ])->columns(2),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomZone')
                    ->label('Zone')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('superficie')
                    ->label('Superficie (km²)')
                    ->numeric(2)
                    ->sortable()
                    ->placeholder('—'),
                TextColumn::make('nombreHabitant')
                    ->label('Habitants')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('signalements_count')
                    ->label('Signalements')
                    ->counts('signalements')
                    ->badge()
                    ->color('primary')
                    ->sortable(),
                TextColumn::make('actifs_count')
                    ->label('Actifs')
                    ->state(function ($record) {
                        return $record->signalements()
                            ->whereIn('statut', ['enAttente', 'enCours'])
                            ->count();
                    })
                    ->badge()
                    ->color(fn ($state) => $state > 5 ? 'danger' : ($state > 2 ? 'warning' : 'success')),
                TextColumn::make('agents_count')
                    ->label('Agents')
                    ->state(fn ($record) => $record->users()->where('role', 'AGENT')->count())
                    ->badge()
                    ->color('info'),
            ])
            ->recordActions([
                ViewAction::make()
                    ->url(fn ($record) => ZoneResource::getUrl('view', ['record' => $record])),
                EditAction::make()
                    ->url(fn ($record) => ZoneResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}

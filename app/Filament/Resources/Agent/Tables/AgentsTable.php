<?php

namespace App\Filament\Resources\Agent\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AgentsTable
{
    public static function configure(Table $table): Table
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
                    ->sortable()
                    ->label('Email'),
                TextColumn::make('zone.nomZone')
                    ->label('Zone')
                    ->badge()
                    ->color('info')
                    ->placeholder('—'),
                TextColumn::make('actif')
                    ->label('Actif')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => $state ? 'Actif' : 'Inactif'),
                TextColumn::make('disponible')
                    ->label('Disponible')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'warning')
                    ->formatStateUsing(fn ($state) => $state ? 'Disponible' : 'Indisponible'),
                TextColumn::make('pointer')
                    ->label('Pointé')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => $state ? 'Pointé' : 'Absent'),
                TextColumn::make('heurePointage')
                    ->label('Heure de pointage')
                    ->dateTime('H:i')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('localite')
                    ->label('Localité')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('—'),
                TextColumn::make('age')
                    ->label('Âge')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('—'),
            ])
            ->filters([
                SelectFilter::make('actif')
                    ->label('État du compte')
                    ->options([
                        '1' => 'Actif',
                        '0' => 'Inactif',
                    ]),
                SelectFilter::make('disponible')
                    ->label('Disponibilité')
                    ->options([
                        '1' => 'Disponible',
                        '0' => 'Indisponible',
                    ]),
                SelectFilter::make('pointer')
                    ->label('Pointage')
                    ->options([
                        '1' => 'Pointé',
                        '0' => 'Non pointé',
                    ]),
                SelectFilter::make('zone_id')
                    ->label('Zone')
                    ->relationship('zone', 'nomZone')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

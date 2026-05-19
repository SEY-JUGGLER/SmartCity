<?php

namespace App\Filament\Resources\Zones\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ZonesTable
{
    public static function configure(Table $table): Table
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
            ->filters([
                //
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

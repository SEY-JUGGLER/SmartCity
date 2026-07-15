<?php

namespace App\Filament\Resources\Communes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CommunesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nom')
                    ->label('Commune')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Description')
                    ->limit(60)
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('zones_count')
                    ->label('Zones')
                    ->counts('zones')
                    ->badge()
                    ->color('primary')
                    ->sortable(),
                TextColumn::make('agents_count')
                    ->label('Agents')
                    ->state(fn ($record) => $record->agents()->count())
                    ->badge()
                    ->color('info'),
                TextColumn::make('signalements_count')
                    ->label('Signalements')
                    ->state(fn ($record) => $record->signalements()->count())
                    ->badge()
                    ->color('warning'),
            ])
            ->filters([])
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

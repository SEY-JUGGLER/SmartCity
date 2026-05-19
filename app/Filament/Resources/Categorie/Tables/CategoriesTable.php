<?php

namespace App\Filament\Resources\Categorie\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nom')
                    ->searchable()
                    ->sortable()
                    ->label('Nom'),
                TextColumn::make('description')
                    ->searchable()
                    ->label('Description')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('priorite')
                    ->label('Priorité')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'critique' => 'danger',
                        'moyenne'  => 'warning',
                        'faible'   => 'success',
                        default    => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'critique' => 'Critique',
                        'moyenne'  => 'Moyenne',
                        'faible'   => 'Faible',
                        default    => $state,
                    })
                    ->sortable(),
                TextColumn::make('active')
                    ->label('Active')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => $state ? 'Active' : 'Inactive'),
                TextColumn::make('signalements_count')
                    ->label('Signalements')
                    ->counts('signalements')
                    ->badge()
                    ->color('primary')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('active')
                    ->label('État')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ]),
                SelectFilter::make('priorite')
                    ->label('Priorité')
                    ->options([
                        'faible'   => 'Faible',
                        'moyenne'  => 'Moyenne',
                        'critique' => 'Critique',
                    ]),
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

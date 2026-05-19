<?php

namespace App\Filament\Resources\Attributions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AttributionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('dateHeureAttribution', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                TextColumn::make('signalement.position')
                    ->label('Signalement')
                    ->searchable()
                    ->limit(30),

                TextColumn::make('signalement.statut')
                    ->label('Statut')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'enAttente' => 'warning',
                        'enCours'   => 'primary',
                        'terminer'  => 'success',
                        'rejeter'   => 'danger',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'enAttente' => 'En attente',
                        'enCours'   => 'En cours',
                        'terminer'  => 'Terminé',
                        'rejeter'   => 'Rejeté',
                        default     => $state,
                    }),

                TextColumn::make('signalement.priorite')
                    ->label('Priorité')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'critique' => 'danger',
                        'moyenne'  => 'warning',
                        'faible'   => 'success',
                        default    => 'gray',
                    }),

                TextColumn::make('agent.prenom')
                    ->label('Agent')
                    ->formatStateUsing(fn ($state, $record) => $record->agent?->prenom . ' ' . $record->agent?->name)
                    ->searchable(),

                TextColumn::make('agent.zone.nomZone')
                    ->label('Zone')
                    ->badge()
                    ->color('info')
                    ->placeholder('—'),

                TextColumn::make('dateHeureAttribution')
                    ->label('Date d\'attribution')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('admin.prenom')
                    ->label('Attribué par')
                    ->formatStateUsing(fn ($state, $record) => $record->admin?->prenom . ' ' . $record->admin?->name)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('signalement.statut')
                    ->label('Statut du signalement')
                    ->relationship('signalement', 'statut')
                    ->options([
                        'enAttente' => 'En attente',
                        'enCours'   => 'En cours',
                        'terminer'  => 'Terminé',
                        'rejeter'   => 'Rejeté',
                    ]),
                SelectFilter::make('agent_id')
                    ->label('Agent')
                    ->relationship('agent', 'prenom'),
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

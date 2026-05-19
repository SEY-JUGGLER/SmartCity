<?php

namespace App\Filament\Resources\Rapports\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;

class RapportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('dateGeneration', 'desc')
            ->columns([
                TextColumn::make('dateGeneration')
                    ->label('Date')
                    ->date('d/m/Y')
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('date_debut')
                    ->label('Période')
                    ->formatStateUsing(fn ($state, $record) =>
                        ($record->date_debut ? $record->date_debut->format('d/m/Y') : '—') .
                        ' → ' .
                        ($record->date_fin   ? $record->date_fin->format('d/m/Y')   : '—')
                    )
                    ->toggleable(),

                TextColumn::make('nbrSignalement')
                    ->label('Signalements')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('taux_resolution')
                    ->label('Résolution')
                    ->formatStateUsing(fn ($state) => $state . '%')
                    ->badge()
                    ->color(fn ($state) => $state >= 70 ? 'success' : ($state >= 40 ? 'warning' : 'danger'))
                    ->sortable(),

                TextColumn::make('taux_refus')
                    ->label('Refus')
                    ->formatStateUsing(fn ($state) => $state . '%')
                    ->badge()
                    ->color(fn ($state) => $state <= 10 ? 'success' : ($state <= 25 ? 'warning' : 'danger'))
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('total_agents')
                    ->label('Agents')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('taux_presence')
                    ->label('Présence')
                    ->formatStateUsing(fn ($state) => $state . '%')
                    ->badge()
                    ->color(fn ($state) => $state >= 80 ? 'success' : ($state >= 60 ? 'warning' : 'danger'))
                    ->toggleable(),

                TextColumn::make('zones_critiques')
                    ->label('Zones critiques')
                    ->badge()
                    ->color(fn ($state) => $state === 0 ? 'success' : 'danger')
                    ->toggleable(),

                TextColumn::make('quantiteOrdure')
                    ->label('Ordures (t)')
                    ->numeric(1)
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('admin.prenom')
                    ->label('Généré par')
                    ->formatStateUsing(fn ($state, $record) =>
                        trim(($record->admin?->prenom ?? '') . ' ' . ($record->admin?->name ?? '')) ?: '—'
                    )
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('date_range')
                    ->label('Période')
                    ->schema([
                        DatePicker::make('date_from')->label('Du')->native(false),
                        DatePicker::make('date_to')->label('Au')->native(false),
                    ])
                    ->query(fn ($query, array $data) => $query
                        ->when($data['date_from'], fn ($q, $v) => $q->whereDate('dateGeneration', '>=', $v))
                        ->when($data['date_to'],   fn ($q, $v) => $q->whereDate('dateGeneration', '<=', $v))
                    ),
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

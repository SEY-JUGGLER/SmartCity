<?php

namespace App\Filament\Resources\Support;

use App\Models\SupportRequest;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class SupportResource extends Resource
{
    protected static ?string $model = SupportRequest::class;

    protected static BackedEnum|string|null $navigationIcon  = 'heroicon-o-lifebuoy';
    protected static UnitEnum|string|null   $navigationGroup = 'Zones & Missions';
    protected static ?string                $navigationLabel = 'Support agents';
    protected static ?int                   $navigationSort  = 3;

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                TextColumn::make('agent.prenom')
                    ->label('Agent')
                    ->formatStateUsing(fn ($state, $record) =>
                        $record->agent ? $record->agent->prenom . ' ' . $record->agent->name : '—'
                    )
                    ->searchable(),

                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'renfort'            => 'warning',
                        'materiel'           => 'info',
                        'panne_vehicule'     => 'danger',
                        'assistance_urgente' => 'danger',
                        default              => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'renfort'            => 'Renfort',
                        'materiel'           => 'Matériel',
                        'panne_vehicule'     => 'Panne véhicule',
                        'assistance_urgente' => 'Assistance urgente',
                        default              => $state,
                    }),

                TextColumn::make('description')
                    ->label('Description')
                    ->limit(60),

                TextColumn::make('statut')
                    ->label('Statut')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'en_attente' => 'warning',
                        'valide'     => 'success',
                        'refusé'     => 'danger',
                        default      => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'en_attente' => 'En attente',
                        'valide'     => 'Validé',
                        'refusé'     => 'Refusé',
                        default      => $state,
                    }),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('date_traitement')
                    ->label('Traitée le')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('—'),
            ])
            ->filters([
                SelectFilter::make('statut')
                    ->options([
                        'en_attente' => 'En attente',
                        'valide'     => 'Validé',
                        'refusé'     => 'Refusé',
                    ]),

                SelectFilter::make('type')
                    ->options([
                        'renfort'            => 'Renfort',
                        'materiel'           => 'Matériel',
                        'panne_vehicule'     => 'Panne véhicule',
                        'assistance_urgente' => 'Assistance urgente',
                    ]),
            ])
            ->recordActions([
                Action::make('valider')
                    ->label('Valider')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (SupportRequest $record) => $record->statut === 'en_attente')
                    ->requiresConfirmation()
                    ->schema([
                        Textarea::make('reponse')
                            ->label('Réponse (optionnelle)')
                            ->rows(2),
                    ])
                    ->action(function (SupportRequest $record) {
                        $record->update([
                            'statut'          => 'valide',
                            'date_traitement' => now(),
                            'traite_par'      => auth()->id(),
                        ]);
                        Notification::make()->title('Demande validée')->success()->send();
                    }),

                Action::make('refuser')
                    ->label('Refuser')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(fn (SupportRequest $record) => $record->statut === 'en_attente')
                    ->requiresConfirmation()
                    ->schema([
                        Textarea::make('motif')
                            ->label('Motif du refus')
                            ->required()
                            ->rows(2),
                    ])
                    ->action(function (SupportRequest $record) {
                        $record->update([
                            'statut'          => 'refusé',
                            'date_traitement' => now(),
                            'traite_par'      => auth()->id(),
                        ]);
                        Notification::make()->title('Demande refusée')->warning()->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('bulk_valider')
                        ->label('Valider la sélection')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(fn ($records) => $records->each(fn ($record) => $record->update([
                            'statut'          => 'valide',
                            'date_traitement' => now(),
                            'traite_par'      => auth()->id(),
                        ]))),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\Support\Pages\ListSupport::route('/'),
        ];
    }
}

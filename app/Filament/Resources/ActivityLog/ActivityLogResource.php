<?php

namespace App\Filament\Resources\ActivityLog;

use App\Models\ActivityLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Journal';
    protected static UnitEnum|string|null $navigationGroup = 'Tableau de bord';
    protected static ?int $navigationSort = 1;

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),

                TextColumn::make('user_name')
                    ->label('Utilisateur')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user_role')
                    ->label('Rôle')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'ADMIN'  => 'danger',
                        'AGENT'  => 'warning',
                        'CITOYEN'=> 'success',
                        default  => 'gray',
                    }),

                TextColumn::make('action')
                    ->label('Action')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'signalement.created'        => 'Signalement créé',
                        'signalement.attributed'     => 'Signalement attribué',
                        'signalement.status_changed' => 'Statut modifié',
                        'support.valide'             => 'Support validé',
                        'support.refuse'             => 'Support refusé',
                        'rapport.generated'          => 'Rapport généré',
                        default                      => $state,
                    })
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        str_contains($state, 'created')   => 'warning',
                        str_contains($state, 'attributed')=> 'info',
                        str_contains($state, 'status')    => 'info',
                        str_contains($state, 'valide')    => 'success',
                        str_contains($state, 'refuse')    => 'danger',
                        str_contains($state, 'generated') => 'primary',
                        default                            => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('target_type')
                    ->label('Type')
                    ->placeholder('—'),

                TextColumn::make('target_id')
                    ->label('Cible #')
                    ->placeholder('—'),

                TextColumn::make('description')
                    ->label('Description')
                    ->limit(80)
                    ->searchable(),

                TextColumn::make('ip_address')
                    ->label('IP')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('action')
                    ->label('Action')
                    ->options([
                        'signalement.created'        => 'Signalement créé',
                        'signalement.attributed'     => 'Signalement attribué',
                        'signalement.status_changed' => 'Statut modifié',
                        'support.valide'             => 'Support validé',
                        'support.refuse'             => 'Support refusé',
                        'rapport.generated'          => 'Rapport généré',
                    ])
                    ->multiple(),

                SelectFilter::make('user_role')
                    ->label('Rôle')
                    ->options([
                        'ADMIN'   => 'Admin',
                        'AGENT'   => 'Agent',
                        'CITOYEN' => 'Citoyen',
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\ActivityLog\Pages\ListActivityLogs::route('/'),
        ];
    }
}

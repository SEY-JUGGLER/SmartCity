<?php

namespace App\Filament\Resources\Signalements;

use App\Filament\Resources\Signalements\Pages\CreateSignalement;
use App\Filament\Resources\Signalements\Pages\EditSignalement;
use App\Filament\Resources\Signalements\Pages\ListSignalements;
use App\Filament\Resources\Signalements\Pages\ViewSignalement;
use App\Filament\Resources\Signalements\Schemas\SignalementInfolist;

use App\Models\Signalement;
use App\Models\User;
use App\Models\Attribution;

use BackedEnum;
use UnitEnum;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;

use Filament\Notifications\Notification;

use Filament\Resources\Resource;

use Filament\Schemas\Schema;

use Filament\Support\Icons\Heroicon;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;

class SignalementResource extends Resource
{
    protected static ?string $model = Signalement::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static ?string $navigationLabel = 'Signalements';
    protected static UnitEnum|string|null $navigationGroup = 'Signalements';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('position')
                ->label('Position / Adresse')
                ->required()
                ->maxLength(255),

            Select::make('categorie_id')
                ->label('Catégorie')
                ->relationship('categorie', 'nom')
                ->searchable()
                ->preload()
                ->required(),

            Select::make('priorite')
                ->label('Priorité')
                ->options([
                    'faible'   => 'Faible',
                    'moyenne'  => 'Moyenne',
                    'critique' => 'Critique',
                ])
                ->default('faible')
                ->required(),

            Select::make('statut')
                ->options([
                    'enAttente' => 'En attente',
                    'enCours'   => 'En cours',
                    'terminer'  => 'Terminé',
                    'rejeter'   => 'Rejeté',
                ])
                ->default('enAttente')
                ->required(),

            Select::make('zone_id')
                ->label('Zone')
                ->relationship('zone', 'nomZone')
                ->searchable()
                ->preload(),

            DatePicker::make('dateSignalement')
                ->label('Date du signalement')
                ->default(today()),

            Textarea::make('description')
                ->rows(4)
                ->required()
                ->columnSpanFull(),

            FileUpload::make('photodoc')
                ->label('Photo')
                ->image()
                ->directory('signalements')
                ->openable()
                ->downloadable()
                ->columnSpanFull(),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SignalementInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                TextColumn::make('categorie.nom')
                    ->label('Catégorie')
                    ->badge()
                    ->color('primary')
                    ->searchable(),

                TextColumn::make('position')
                    ->label('Position')
                    ->limit(35)
                    ->searchable(),

                TextColumn::make('priorite')
                    ->label('Priorité')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
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

                TextColumn::make('statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
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

                TextColumn::make('zone.nomZone')
                    ->label('Zone')
                    ->badge()
                    ->color('info')
                    ->placeholder('—'),

                TextColumn::make('citoyen.prenom')
                    ->label('Citoyen')
                    ->formatStateUsing(fn ($state, $record) => $record->citoyen?->prenom . ' ' . $record->citoyen?->name)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('attribution.agent.prenom')
                    ->label('Agent')
                    ->formatStateUsing(fn ($state, $record) =>
                        $record->attribution?->agent
                            ? $record->attribution->agent->prenom . ' ' . $record->attribution->agent->name
                            : '—'
                    )
                    ->placeholder('—'),

                TextColumn::make('dateSignalement')
                    ->label('Date')
                    ->date('d/m/Y')
                    ->sortable(),
            ])

            ->filters([
                SelectFilter::make('statut')
                    ->label('Statut')
                    ->options([
                        'enAttente' => 'En attente',
                        'enCours'   => 'En cours',
                        'terminer'  => 'Terminé',
                        'rejeter'   => 'Rejeté',
                    ]),

                SelectFilter::make('priorite')
                    ->label('Priorité')
                    ->options([
                        'faible'   => 'Faible',
                        'moyenne'  => 'Moyenne',
                        'critique' => 'Critique',
                    ]),

                SelectFilter::make('categorie_id')
                    ->label('Catégorie')
                    ->relationship('categorie', 'nom')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('zone_id')
                    ->label('Zone')
                    ->relationship('zone', 'nomZone')
                    ->searchable()
                    ->preload(),

                Filter::make('date_range')
                    ->label('Période')
                    ->schema([
                        DatePicker::make('date_from')->label('Du'),
                        DatePicker::make('date_to')->label('Au'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['date_from'], fn ($q, $v) => $q->whereDate('dateSignalement', '>=', $v))
                            ->when($data['date_to'],   fn ($q, $v) => $q->whereDate('dateSignalement', '<=', $v));
                    }),

                Filter::make('non_attribues')
                    ->label('Non attribués')
                    ->query(fn ($query) => $query->doesntHave('attribution')),
            ])

            ->recordActions([
                ViewAction::make(),
                EditAction::make(),

                Action::make('attribuer')
                    ->label('Attribuer')
                    ->icon(Heroicon::OutlinedUserPlus)
                    ->color('success')
                    ->visible(fn (Signalement $record) => ! $record->attribution)
                    ->schema([
                        Select::make('agent_id')
                            ->label('Agent disponible')
                            ->options(
                                User::where('role', 'AGENT')
                                    ->where('actif', true)
                                    ->where('disponible', true)
                                    ->get()
                                    ->mapWithKeys(fn ($u) => [$u->id => $u->prenom . ' ' . $u->name])
                            )
                            ->searchable()
                            ->required(),
                    ])
                    ->action(function (Signalement $record, array $data) {
                        Attribution::create([
                            'signalement_id'       => $record->id,
                            'agent_id'             => $data['agent_id'],
                            'admin_id'             => auth()->id(),
                            'dateHeureAttribution' => now(),
                        ]);

                        Notification::make()
                            ->title('Signalement attribué avec succès')
                            ->success()
                            ->send();
                    }),

                Action::make('reattribuer')
                    ->label('Réattribuer')
                    ->icon(Heroicon::OutlinedArrowPath)
                    ->color('warning')
                    ->visible(fn (Signalement $record) => $record->attribution !== null && $record->statut !== 'terminer')
                    ->schema([
                        Select::make('agent_id')
                            ->label('Nouvel agent')
                            ->options(
                                User::where('role', 'AGENT')
                                    ->where('actif', true)
                                    ->where('disponible', true)
                                    ->get()
                                    ->mapWithKeys(fn ($u) => [$u->id => $u->prenom . ' ' . $u->name])
                            )
                            ->searchable()
                            ->required(),
                    ])
                    ->action(function (Signalement $record, array $data) {
                        $record->attribution->update([
                            'agent_id'             => $data['agent_id'],
                            'admin_id'             => auth()->id(),
                            'dateHeureAttribution' => now(),
                        ]);

                        Notification::make()
                            ->title('Signalement réattribué')
                            ->success()
                            ->send();
                    }),

                Action::make('changer_statut')
                    ->label('Statut')
                    ->icon('heroicon-o-arrow-path-rounded-square')
                    ->color('info')
                    ->schema([
                        Select::make('statut')
                            ->label('Nouveau statut')
                            ->options([
                                'enAttente' => 'En attente',
                                'enCours'   => 'En cours',
                                'terminer'  => 'Terminé',
                                'rejeter'   => 'Rejeté',
                            ])
                            ->required(),
                    ])
                    ->action(function (Signalement $record, array $data) {
                        $record->update(['statut' => $data['statut']]);
                        Notification::make()
                            ->title('Statut mis à jour')
                            ->success()
                            ->send();
                    }),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListSignalements::route('/'),
            'create' => CreateSignalement::route('/create'),
            'view'   => ViewSignalement::route('/{record}'),
            'edit'   => EditSignalement::route('/{record}/edit'),
        ];
    }
}

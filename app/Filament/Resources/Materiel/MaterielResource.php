<?php

namespace App\Filament\Resources\Materiel;

use App\Models\Materiel;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use BackedEnum;
use UnitEnum;

class MaterielResource extends Resource
{
    protected static ?string $model = Materiel::class;

    protected static BackedEnum|string|null $navigationIcon  = 'heroicon-o-wrench-screwdriver';
    protected static UnitEnum|string|null   $navigationGroup = 'Zones & Missions';
    protected static ?string                $navigationLabel = 'Matériels';
    protected static ?int                   $navigationSort  = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('nom')
                ->label('Nom du matériel')
                ->required(),

            Textarea::make('description')
                ->label('Description')
                ->rows(2),

            TextInput::make('categorie')
                ->label('Catégorie')
                ->required(),

            Select::make('statut')
                ->label('Statut')
                ->required()
                ->default('disponible')
                ->options([
                    'disponible'      => 'Disponible',
                    'attribue'        => 'Attribué',
                    'en_maintenance'  => 'En maintenance',
                    'hors_service'    => 'Hors service',
                ]),

            Select::make('agent_id')
                ->label('Assigné à')
                ->searchable()
                ->nullable()
                ->options(fn () => \App\Models\User::where('role', 'AGENT')
                    ->orderBy('prenom')
                    ->get()
                    ->mapWithKeys(fn ($u) => [$u->id => $u->prenom . ' ' . $u->name])
                    ->toArray()
                )
                ->getSearchResultsUsing(fn (string $search) =>
                    \App\Models\User::where('role', 'AGENT')
                        ->where(fn ($q) => $q
                            ->where('prenom', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$search}%")
                        )
                        ->orderBy('prenom')
                        ->limit(20)
                        ->get()
                        ->mapWithKeys(fn ($u) => [$u->id => $u->prenom . ' ' . $u->name])
                        ->toArray()
                )
                ->getOptionLabelUsing(fn ($value) => optional(\App\Models\User::find($value))->prenom . ' ' . optional(\App\Models\User::find($value))->name),

            DatePicker::make('date_attribution')
                ->label("Date d'attribution")
                ->native(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('nom')
                    ->label('Matériel')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Description')
                    ->limit(40)
                    ->toggleable(),

                TextColumn::make('categorie')
                    ->label('Catégorie')
                    ->badge()
                    ->sortable(),

                TextColumn::make('statut')
                    ->label('Statut')
                    ->badge()
                    ->sortable()
                    ->color(fn ($state) => match ($state) {
                        'disponible'     => 'success',
                        'attribue'       => 'warning',
                        'en_maintenance' => 'info',
                        'hors_service'   => 'danger',
                        default          => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'disponible'     => 'Disponible',
                        'attribue'       => 'Attribué',
                        'en_maintenance' => 'En maintenance',
                        'hors_service'   => 'Hors service',
                        default          => $state,
                    }),

                TextColumn::make('agent.prenom')
                    ->label('Assigné à')
                    ->formatStateUsing(fn ($state, $record) =>
                        $record->agent ? $record->agent->prenom . ' ' . $record->agent->name : '—'
                    )
                    ->searchable(),

                TextColumn::make('date_attribution')
                    ->label('Attribué le')
                    ->date('d/m/Y')
                    ->placeholder('—'),
            ])
            ->filters([
                SelectFilter::make('statut')
                    ->options([
                        'disponible'     => 'Disponible',
                        'attribue'       => 'Attribué',
                        'en_maintenance' => 'En maintenance',
                        'hors_service'   => 'Hors service',
                    ]),

                SelectFilter::make('categorie')
                    ->options(fn () => Materiel::distinct()->pluck('categorie', 'categorie')->toArray()),
            ])
            ->toolbarActions([
                CreateAction::make()->label('Nouveau matériel'),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\Materiel\Pages\ListMateriels::route('/'),
        ];
    }
}

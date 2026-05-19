<?php

namespace App\Filament\Resources\Rapports\Schemas;

use App\Models\Rapport;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RapportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([

            Section::make('Période du rapport')
                ->description('Définissez la période puis cliquez sur "Calculer" pour auto-remplir toutes les statistiques.')
                ->icon('heroicon-o-calendar-days')
                ->headerActions([
                    Action::make('calculer')
                        ->label('Calculer automatiquement')
                        ->icon('heroicon-o-arrow-path')
                        ->color('primary')
                        ->action(function (Get $get, Set $set) {
                            $stats = Rapport::calculerStats(
                                $get('date_debut'),
                                $get('date_fin')
                            );
                            foreach ($stats as $key => $value) {
                                $set($key, $value);
                            }
                        }),
                ])
                ->schema([
                    DatePicker::make('dateGeneration')
                        ->label('Date de génération')
                        ->default(today())
                        ->required()
                        ->native(false),

                    DatePicker::make('date_debut')
                        ->label('Période — Du')
                        ->default(today()->startOfMonth())
                        ->native(false),

                    DatePicker::make('date_fin')
                        ->label('Période — Au')
                        ->default(today())
                        ->native(false),
                ])->columns(3),

            Section::make('Signalements')
                ->icon('heroicon-o-exclamation-triangle')
                ->schema([
                    TextInput::make('nbrSignalement')
                        ->label('Total signalements')
                        ->numeric()
                        ->default(0)
                        ->required(),

                    TextInput::make('nbr_en_attente')
                        ->label('En attente')
                        ->numeric()
                        ->default(0),

                    TextInput::make('nbr_en_cours')
                        ->label('En cours')
                        ->numeric()
                        ->default(0),

                    TextInput::make('nbr_termines')
                        ->label('Terminés')
                        ->numeric()
                        ->default(0),

                    TextInput::make('nbr_rejetes')
                        ->label('Rejetés')
                        ->numeric()
                        ->default(0),

                    TextInput::make('nbr_critiques')
                        ->label('Critiques actifs')
                        ->numeric()
                        ->default(0),
                ])->columns(3),

            Section::make('Performance')
                ->icon('heroicon-o-chart-bar')
                ->schema([
                    TextInput::make('taux_resolution')
                        ->label('Taux de résolution (%)')
                        ->numeric()
                        ->step(0.01)
                        ->suffix('%')
                        ->default(0),

                    TextInput::make('taux_refus')
                        ->label('Taux de refus (%)')
                        ->numeric()
                        ->step(0.01)
                        ->suffix('%')
                        ->default(0),

                    TextInput::make('temps_moyen_traitement_h')
                        ->label('Temps moyen traitement (h)')
                        ->numeric()
                        ->step(0.01)
                        ->suffix('h')
                        ->nullable(),

                    TextInput::make('temps_moyen_acceptation_h')
                        ->label('Temps moyen acceptation (h)')
                        ->numeric()
                        ->step(0.01)
                        ->suffix('h')
                        ->nullable(),

                    TextInput::make('quantiteOrdure')
                        ->label("Quantité d'ordures collectées (t)")
                        ->numeric()
                        ->step(0.1)
                        ->suffix('t')
                        ->nullable(),
                ])->columns(3),

            Section::make('Agents')
                ->icon('heroicon-o-users')
                ->schema([
                    TextInput::make('total_agents')
                        ->label('Total agents')
                        ->numeric()
                        ->default(0),

                    TextInput::make('agents_disponibles')
                        ->label('Disponibles')
                        ->numeric()
                        ->default(0),

                    TextInput::make('agents_occupes')
                        ->label('Occupés')
                        ->numeric()
                        ->default(0),

                    TextInput::make('agents_absents')
                        ->label('Absents (non pointés)')
                        ->numeric()
                        ->default(0),

                    TextInput::make('agents_inactifs')
                        ->label('Inactifs')
                        ->numeric()
                        ->default(0),

                    TextInput::make('taux_presence')
                        ->label('Taux de présence (%)')
                        ->numeric()
                        ->step(0.01)
                        ->suffix('%')
                        ->default(0),
                ])->columns(3),

            Section::make('Zones')
                ->icon('heroicon-o-map')
                ->schema([
                    TextInput::make('total_zones')
                        ->label('Total zones')
                        ->numeric()
                        ->default(0),

                    TextInput::make('zones_critiques')
                        ->label('Zones critiques (> 5 actifs)')
                        ->numeric()
                        ->default(0),
                ])->columns(2),

            Section::make('Notes')
                ->icon('heroicon-o-document-text')
                ->schema([
                    Textarea::make('notes')
                        ->label('Observations et recommandations')
                        ->rows(4)
                        ->placeholder('Saisissez vos observations ici...')
                        ->nullable()
                        ->columnSpanFull(),
                ]),
        ]);
    }
}

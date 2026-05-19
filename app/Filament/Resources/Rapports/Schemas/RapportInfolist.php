<?php

namespace App\Filament\Resources\Rapports\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RapportInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Informations générales')
                ->icon('heroicon-o-document-chart-bar')
                ->schema([
                    TextEntry::make('dateGeneration')
                        ->label('Date de génération')
                        ->date('d/m/Y')
                        ->badge()
                        ->color('primary'),

                    TextEntry::make('admin.prenom')
                        ->label('Généré par')
                        ->formatStateUsing(fn ($state, $record) =>
                            trim(($record->admin?->prenom ?? '') . ' ' . ($record->admin?->name ?? '')) ?: '—'
                        ),

                    TextEntry::make('created_at')
                        ->label('Enregistré le')
                        ->dateTime('d/m/Y H:i'),

                    TextEntry::make('date_debut')
                        ->label('Période — Du')
                        ->date('d/m/Y')
                        ->placeholder('—'),

                    TextEntry::make('date_fin')
                        ->label('Période — Au')
                        ->date('d/m/Y')
                        ->placeholder('—'),
                ])->columns(3),

            Section::make('Signalements')
                ->icon('heroicon-o-exclamation-triangle')
                ->schema([
                    TextEntry::make('nbrSignalement')
                        ->label('Total')
                        ->badge()
                        ->color('primary'),

                    TextEntry::make('nbr_en_attente')
                        ->label('En attente')
                        ->badge()
                        ->color('warning'),

                    TextEntry::make('nbr_en_cours')
                        ->label('En cours')
                        ->badge()
                        ->color('info'),

                    TextEntry::make('nbr_termines')
                        ->label('Terminés')
                        ->badge()
                        ->color('success'),

                    TextEntry::make('nbr_rejetes')
                        ->label('Rejetés')
                        ->badge()
                        ->color('danger'),

                    TextEntry::make('nbr_critiques')
                        ->label('Critiques actifs')
                        ->badge()
                        ->color(fn ($state) => $state > 0 ? 'danger' : 'success'),
                ])->columns(3),

            Section::make('Performance')
                ->icon('heroicon-o-chart-bar')
                ->schema([
                    TextEntry::make('taux_resolution')
                        ->label('Taux de résolution')
                        ->formatStateUsing(fn ($state) => $state . '%')
                        ->badge()
                        ->color(fn ($state) => $state >= 70 ? 'success' : ($state >= 40 ? 'warning' : 'danger')),

                    TextEntry::make('taux_refus')
                        ->label('Taux de refus')
                        ->formatStateUsing(fn ($state) => $state . '%')
                        ->badge()
                        ->color(fn ($state) => $state <= 10 ? 'success' : ($state <= 25 ? 'warning' : 'danger')),

                    TextEntry::make('temps_moyen_traitement_h')
                        ->label('Temps moyen traitement')
                        ->formatStateUsing(fn ($state) => $state ? $state . 'h' : '—')
                        ->badge()
                        ->color(fn ($state) => ($state ?? 0) <= 24 ? 'success' : 'warning'),

                    TextEntry::make('temps_moyen_acceptation_h')
                        ->label('Temps moyen acceptation')
                        ->formatStateUsing(fn ($state) => $state ? $state . 'h' : '—')
                        ->badge()
                        ->color(fn ($state) => ($state ?? 0) <= 1 ? 'success' : (($state ?? 0) <= 4 ? 'warning' : 'danger')),

                    TextEntry::make('quantiteOrdure')
                        ->label('Ordures collectées')
                        ->formatStateUsing(fn ($state) => $state ? $state . ' t' : '—')
                        ->placeholder('—'),
                ])->columns(3),

            Section::make('Agents')
                ->icon('heroicon-o-users')
                ->schema([
                    TextEntry::make('total_agents')
                        ->label('Total agents')
                        ->badge()
                        ->color('primary'),

                    TextEntry::make('agents_disponibles')
                        ->label('Disponibles')
                        ->badge()
                        ->color(fn ($state) => $state >= 3 ? 'success' : ($state >= 1 ? 'warning' : 'danger')),

                    TextEntry::make('agents_occupes')
                        ->label('Occupés')
                        ->badge()
                        ->color('warning'),

                    TextEntry::make('agents_absents')
                        ->label('Absents')
                        ->badge()
                        ->color(fn ($state) => $state > 0 ? 'danger' : 'success'),

                    TextEntry::make('agents_inactifs')
                        ->label('Inactifs')
                        ->badge()
                        ->color(fn ($state) => $state > 0 ? 'danger' : 'success'),

                    TextEntry::make('taux_presence')
                        ->label('Taux de présence')
                        ->formatStateUsing(fn ($state) => $state . '%')
                        ->badge()
                        ->color(fn ($state) => $state >= 80 ? 'success' : ($state >= 60 ? 'warning' : 'danger')),
                ])->columns(3),

            Section::make('Zones')
                ->icon('heroicon-o-map')
                ->schema([
                    TextEntry::make('total_zones')
                        ->label('Total zones')
                        ->badge()
                        ->color('primary'),

                    TextEntry::make('zones_critiques')
                        ->label('Zones critiques')
                        ->badge()
                        ->color(fn ($state) => $state === 0 ? 'success' : 'danger'),
                ])->columns(2),

            Section::make('Notes')
                ->icon('heroicon-o-document-text')
                ->schema([
                    TextEntry::make('notes')
                        ->label('')
                        ->placeholder('Aucune note')
                        ->columnSpanFull(),
                ])
                ->collapsible()
                ->collapsed(fn ($record) => !$record->notes),
        ]);
    }
}

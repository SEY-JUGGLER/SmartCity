<?php

namespace App\Filament\Resources\Signalements\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SignalementInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informations du signalement')
                ->schema([
                    TextEntry::make('categorie.nom')
                        ->label('Catégorie')
                        ->badge()
                        ->color('primary'),
                    TextEntry::make('priorite')
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
                        }),
                    TextEntry::make('statut')
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
                    TextEntry::make('dateSignalement')
                        ->label('Date')
                        ->date('d/m/Y'),
                ])->columns(4),

            Section::make('Localisation & Description')
                ->schema([
                    TextEntry::make('position')
                        ->label('Position / Adresse')
                        ->columnSpanFull(),
                    TextEntry::make('zone.nomZone')
                        ->label('Zone')
                        ->badge()
                        ->color('info')
                        ->placeholder('—'),
                    TextEntry::make('description')
                        ->label('Description')
                        ->columnSpanFull(),
                ])->columns(2),

            Section::make('Photo')
                ->schema([
                    ImageEntry::make('photodoc')
                        ->label('')
                        ->disk('public')
                        ->columnSpanFull()
                        ->placeholder('Aucune photo'),
                ])
                ->visible(fn ($record) => filled($record->photodoc)),

            Section::make('Citoyen')
                ->schema([
                    TextEntry::make('citoyen.prenom')
                        ->label('Prénom'),
                    TextEntry::make('citoyen.name')
                        ->label('Nom'),
                    TextEntry::make('citoyen.email')
                        ->label('Email'),
                ])->columns(3),

            Section::make('Attribution')
                ->schema([
                    TextEntry::make('attribution.agent.prenom')
                        ->label('Agent assigné')
                        ->formatStateUsing(fn ($state, $record) =>
                            $record->attribution?->agent
                                ? $record->attribution->agent->prenom . ' ' . $record->attribution->agent->name
                                : '—'
                        ),
                    TextEntry::make('attribution.dateHeureAttribution')
                        ->label('Attribué le')
                        ->dateTime('d/m/Y H:i')
                        ->placeholder('—'),
                ])->columns(2),
        ]);
    }
}

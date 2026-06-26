<?php

namespace App\Filament\Resources\Attributions\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AttributionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Détails de l\'attribution')
                ->schema([
                    TextEntry::make('dateHeureAttribution')
                        ->label('Date d\'attribution')
                        ->dateTime('d/m/Y H:i'),
                    TextEntry::make('admin.prenom')
                        ->label('Attribué par (admin)')
                        ->formatStateUsing(fn ($state, $record) => $record->admin?->prenom . ' ' . $record->admin?->name),
                ])->columns(2),

            Section::make('Signalement')
                ->schema([
                    TextEntry::make('signalement.position')
                        ->label('Position'),
                    TextEntry::make('signalement.statut')
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
                    TextEntry::make('signalement.priorite')
                        ->label('Priorité')
                        ->badge()
                        ->color(fn ($state) => match ($state) {
                            'critique' => 'danger',
                            'moyenne'  => 'warning',
                            'faible'   => 'success',
                            default    => 'gray',
                        }),
                    TextEntry::make('signalement.description')
                        ->label('Description')
                        ->columnSpanFull(),
                ])->columns(2),

            Section::make('Agent assigné')
                ->schema([
                    TextEntry::make('agent.prenom')
                        ->label('Prénom'),
                    TextEntry::make('agent.name')
                        ->label('Nom'),
                    TextEntry::make('agent.email')
                        ->label('Email'),
                    TextEntry::make('agent.zone.nomZone')
                        ->label('Zone')
                        ->placeholder('—'),
                ])->columns(2),
        ]);
    }
}

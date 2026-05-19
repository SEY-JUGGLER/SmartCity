<?php

namespace App\Filament\Resources\Zones\Schemas;

//use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class ZoneInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informations de la zone')
                ->schema([
                    TextEntry::make('nomZone')
                        ->label('Nom de la zone'),
                    TextEntry::make('superficie')
                        ->label('Superficie (km²)')
                        ->placeholder('—'),
                    TextEntry::make('nombreHabitant')
                        ->label('Nombre d\'habitants')
                        ->numeric(),
                    TextEntry::make('created_at')
                        ->label('Créée le')
                        ->dateTime('d/m/Y'),
                ])->columns(2),

            Section::make('Statistiques')
                ->schema([
                    TextEntry::make('signalements_count')
                        ->label('Total signalements')
                        ->state(fn ($record) => $record->signalements()->count())
                        ->badge()
                        ->color('primary'),
                    TextEntry::make('signalements_actifs')
                        ->label('Signalements actifs')
                        ->state(fn ($record) => $record->signalements()->whereIn('statut', ['enAttente', 'enCours'])->count())
                        ->badge()
                        ->color('warning'),
                    TextEntry::make('signalements_termines')
                        ->label('Signalements terminés')
                        ->state(fn ($record) => $record->signalements()->where('statut', 'terminer')->count())
                        ->badge()
                        ->color('success'),
                    TextEntry::make('agents_count')
                        ->label('Agents dans la zone')
                        ->state(fn ($record) => $record->users()->where('role', 'AGENT')->count())
                        ->badge()
                        ->color('info'),
                ])->columns(2),
        ]);
    }
}

<?php

namespace App\Filament\Resources\Communes\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CommuneInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informations de la commune')
                ->schema([
                    TextEntry::make('nom')
                        ->label('Nom'),
                    TextEntry::make('description')
                        ->label('Description')
                        ->placeholder('—'),
                    TextEntry::make('created_at')
                        ->label('Créée le')
                        ->dateTime('d/m/Y'),
                ])->columns(2),

            Section::make('Statistiques')
                ->schema([
                    TextEntry::make('zones_count')
                        ->label('Zones')
                        ->state(fn ($record) => $record->zones()->count())
                        ->badge()
                        ->color('primary'),
                    TextEntry::make('agents_count')
                        ->label('Agents')
                        ->state(fn ($record) => $record->agents()->count())
                        ->badge()
                        ->color('info'),
                    TextEntry::make('signalements_count')
                        ->label('Signalements')
                        ->state(fn ($record) => $record->signalements()->count())
                        ->badge()
                        ->color('warning'),
                ])->columns(3),
        ]);
    }
}

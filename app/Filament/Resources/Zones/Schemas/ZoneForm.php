<?php

namespace App\Filament\Resources\Zones\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class ZoneForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Informations de la zone')
                ->schema([
                    TextInput::make('nomZone')
                        ->label('Nom de la zone')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('superficie')
                        ->label('Superficie (km²)')
                        ->numeric()
                        ->step(0.01)
                        ->minValue(0),
                    TextInput::make('nombreHabitant')
                        ->label('Nombre d\'habitants')
                        ->numeric()
                        ->minValue(0)
                        ->default(0),
                ])->columns(2),
        ]);
    }
}

<?php

namespace App\Filament\Resources\Communes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class CommuneForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Informations de la commune')
                ->schema([
                    TextInput::make('nom')
                        ->label('Nom de la commune')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('description')
                        ->label('Description')
                        ->maxLength(1000),
                ]),
        ]);
    }
}

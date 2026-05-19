<?php

namespace App\Filament\Resources\Categorie\Schemas;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CategorieForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Informations de la catégorie')
                ->schema([
                    TextInput::make('nom')
                        ->label('Nom')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('description')
                        ->label('Description')
                        ->maxLength(500),
                    Select::make('priorite')
                        ->label('Priorité par défaut')
                        ->options([
                            'faible'    => 'Faible',
                            'moyenne'   => 'Moyenne',
                            'critique'  => 'Critique',
                        ])
                        ->default('faible')
                        ->required(),
                    Toggle::make('active')
                        ->label('Catégorie active')
                        ->default(true),
                ])
                ->columns(2),
        ]);
    }
}
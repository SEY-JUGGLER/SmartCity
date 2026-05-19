<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Informations personnelles')
                ->schema([
                    TextInput::make('nom')->label('Nom')->required(),
                    TextInput::make('prenom')->label('Prénom')->required(),
                    TextInput::make('email')->label('Email')->email()->required()->unique(ignoreRecord: true),
                    TextInput::make('age')->label('Âge')->numeric()->minValue(0)->maxValue(120),
                    TextInput::make('localite')->label('Localité'),
                ])
                ->columns(2),

            Section::make('Statut du compte')
                ->schema([
                    Toggle::make('actif')->label('Compte actif')->default(true),
                    Toggle::make('compteBloque')->label('Compte bloqué')->default(false),
                ])
                ->columns(2),
        ]);
    }
}
<?php

namespace App\Filament\Resources\Admin\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class AdminForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informations personnelles')
                    ->schema([
                        TextInput::make('prenom')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('name')
                            ->label('Nom')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord:true),
                        TextInput::make('password')
                            ->label('Mot de passe')
                            ->password()
                            ->required(fn($live) => !$live)
                            ->maxLength(255)
                            ->dehydrated(fn($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                            ->visible(fn($live) => !$live),
                        TextInput::make('age')
                            ->numeric()
                            ->required()
                            ->maxLength(3),
                        TextInput::make('localite')
                            ->maxLength(255),
                        FileUpload::make('photoProfi')
                            ->image()
                            ->directory('admins')
                            ->maxSize(1024),
                    ])->columns(2),
                Section::make('Statut et disponibilité')
                    ->schema([
    
                        Toggle::make('actif')
                            ->label('Compte actif')
                            ->default(true),
                    ])->columns(2),
                Section::make('Sécurité')
                    ->schema([
                        Toggle::make('compteBloque')
                            ->label('Compte bloqué')
                            ->default(false),
                    ])->columns(1),
            ]);
    }
}
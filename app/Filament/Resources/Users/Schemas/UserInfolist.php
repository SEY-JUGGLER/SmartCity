<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informations personnelles')
                ->schema([
                    TextEntry::make('prenom')->label('Prénom'),
                    TextEntry::make('name')->label('Nom'),
                    TextEntry::make('email')->label('Email'),
                    TextEntry::make('localite')->label('Localité')->placeholder('—'),
                    TextEntry::make('created_at')->label('Inscription')->date('d/m/Y'),
                ])->columns(3),

            Section::make('Statut du compte')
                ->schema([
                    TextEntry::make('actif')
                        ->label('Actif')
                        ->badge()
                        ->color(fn ($state) => $state ? 'success' : 'danger')
                        ->formatStateUsing(fn ($state) => $state ? 'Actif' : 'Inactif'),
                    TextEntry::make('compteBloque')
                        ->label('Compte bloqué')
                        ->badge()
                        ->color(fn ($state) => $state ? 'danger' : 'success')
                        ->formatStateUsing(fn ($state) => $state ? 'Bloqué' : 'Normal'),
                ])->columns(2),

            Section::make('Activité')
                ->schema([
                    TextEntry::make('signalements_count')
                        ->label('Total signalements')
                        ->state(fn ($record) => $record->hasMany(\App\Models\Signalement::class, 'user_id')->count())
                        ->badge()
                        ->color('primary'),
                ])->columns(2),
        ]);
    }
}

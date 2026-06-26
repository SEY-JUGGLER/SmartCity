<?php
namespace App\Filament\Resources\Signalements\Schemas;

use App\Models\Categorie;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SignalementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

             TextInput::make('Informations du signalement')
                 ->schema([
                     TextInput::make('position')
                         ->required()
                         ->label('Position / Adresse')
                       ->columnSpanFull(),

                     Select::make('categorie_id')
                         ->label('Catégorie')
                         ->options(Categorie::where('active', true)->pluck('nom', 'id'))
                         ->searchable()
                         ->preload()
                         ->required()
                         ->native(false),

                     Select::make('priorite')
                         ->label('Priorité')
                         ->options([
                             'faible'    => 'Faible',
                             'moyenne'   => 'Moyenne',
                             'critique'  => 'Critique',
                         ])
                         ->default('faible')
                         ->required()
                         ->native(false),

                     Select::make('statut')
                         ->label('Statut')
                         ->options([
                             'enAttente' => 'En attente',
                             'enCours'   => 'En cours',
                             'terminer'  => 'Terminé',
                             'rejeter'   => 'Rejeté',
                         ])
                         ->required()
                         ->native(false),

                     Select::make('zone_id')
                         ->label('Zone')
                         ->relationship('zone', 'nomZone')
                         ->searchable()
                         ->preload()
                         ->native(false),

                     Textarea::make('description')
                         ->label('Description')
                         ->required()
                         ->rows(4)
                         ->columnSpanFull(),

                     Textarea::make('commentaire_admin')
                         ->label('Commentaire administrateur')
                         ->rows(3)
                         ->columnSpanFull(),

                     FileUpload::make('photodoc')
                         ->label('Photo')
                         ->image()
                         ->directory('signalements')
                         ->columnSpanFull(),
                 ])
                 ->columns(2),
         ]);
    }
}
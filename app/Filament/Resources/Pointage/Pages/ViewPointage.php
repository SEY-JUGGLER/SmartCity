<?php

namespace App\Filament\Resources\Pointage\Pages;

use App\Filament\Resources\Pointage\PointageResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables;
use App\Models\User;

class ViewPointage extends ViewRecord
{
    protected static string $resource = PointageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // We can add actions like forcing availability, etc.
            // For now, we'll leave it empty or add a note.
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('Informations de l\'agent')
                ->schema([
                    TextInput::make('prenom')
                        ->label('Prénom')
                        ->readOnly(),
                    TextInput::make('nom')
                        ->label('Nom')
                        ->readOnly(),
                    TextInput::make('email')
                        ->label('Email')
                        ->readOnly(),
                    TextInput::make('telephone')
                        ->label('Téléphone')
                        ->readOnly(),
                    TextInput::make('localite')
                        ->label('Localité')
                        ->readOnly(),
                ])
                ->columns(2),

            Section::make('Statut de pointage')
                ->schema([
                    Toggle::make('pointer')
                        ->label('Pointé présent')
                        ->disabled()
                        ->live(false),
                    DateTimePicker::make('heurePointage')
                        ->label('Heure de pointage')
                        ->readOnly()
                        ->disabled(),
                    Toggle::make('disponible')
                        ->label('Disponible pour mission')
                        ->disabled()
                        ->live(false),
                    Toggle::make('actif')
                        ->label('Compte actif')
                        ->disabled()
                        ->live(false),
                ])
                ->columns(2),

            Section::make('Localisation (si disponible)')
                ->schema([
                    // We could show a map or last known location from Localisation model
                    // For now, we'll leave it as a placeholder.
                    Forms\Components\TextInput::make('derniere_localisation')
                        ->label('Dernière localisation connue')
                        ->readOnly()
                        ->placeholder('Aucune donnée de localisation'),
                ])
                ->columns(1),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $loc  = $this->record->localisations()->latest('dateHeure')->first();
        $zone = $this->record->zone;

        if ($loc?->latitude !== null && $loc?->longitude !== null) {
            $data['derniere_localisation'] = number_format((float) $loc->latitude, 6)
                . ', ' . number_format((float) $loc->longitude, 6)
                . ' — GPS (temps réel)';
        } elseif ($zone?->latitude !== null && $zone?->longitude !== null) {
            $data['derniere_localisation'] = number_format((float) $zone->latitude, 6)
                . ', ' . number_format((float) $zone->longitude, 6)
                . ' — Zone : ' . $zone->nomZone;
        } else {
            $data['derniere_localisation'] = 'Non disponible';
        }

        return $data;
    }
}
<?php

namespace App\Filament\Resources\Attributions\Schemas;

use App\Models\Signalement;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class AttributionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Attribution de mission')
                ->schema([
                    Select::make('signalement_id')
                        ->label('Signalement')
                        ->options(
                            Signalement::whereIn('statut', ['enAttente'])
                                ->get()
                                ->mapWithKeys(fn ($s) => [$s->id => "#{$s->id} — {$s->position} ({$s->statut})"])
                        )
                        ->searchable()
                        ->required(),

                    Select::make('agent_id')
                        ->label('Agent')
                        ->options(
                            User::where('role', 'AGENT')
                                ->where('actif', true)
                                ->where('disponible', true)
                                ->get()
                                ->mapWithKeys(fn ($u) => [$u->id => $u->prenom . ' ' . $u->name])
                        )
                        ->searchable()
                        ->required(),

                    DateTimePicker::make('dateHeureAttribution')
                        ->label('Date et heure d\'attribution')
                        ->default(now())
                        ->required(),
                ])->columns(2),
        ]);
    }
}

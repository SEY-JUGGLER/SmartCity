<?php

namespace App\Filament\Resources\Attributions\Schemas;

use App\Models\Signalement;
use App\Models\User;
use App\Services\AttributionService;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Schemas\Components\Section;
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
                                ->mapWithKeys(fn ($s) => [
                                    $s->id => "#$s->id — {$s->position}" . ($s->latitude ? ' 📍' : ' (sans GPS)'),
                                ])
                        )
                        ->searchable()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state, Set $set): void {
                            $set('agent_id', null);

                            if (! $state) {
                                return;
                            }

                            $sig = Signalement::find($state);
                            if (! $sig) {
                                return;
                            }

                            $nearest = app(AttributionService::class)->findNearestAgent($sig);
                            if ($nearest) {
                                $set('agent_id', $nearest->id);
                            }
                        }),

                    Select::make('agent_id')
                        ->label('Agent')
                        ->options(function (Get $get): array {
                            $svc   = app(AttributionService::class);
                            $sigId = $get('signalement_id');

                            if (! $sigId) {
                                return User::where('role', 'AGENT')
                                    ->where('actif', true)
                                    ->where('disponible', true)
                                    ->with('zone')
                                    ->orderBy('prenom')
                                    ->get()
                                    ->mapWithKeys(fn ($u) => [
                                        $u->id => "{$u->prenom} {$u->name}"
                                            . ($u->zone ? " · {$u->zone->nomZone}" : ''),
                                    ])
                                    ->toArray();
                            }

                            $sig = Signalement::find($sigId);
                            if (! $sig) {
                                return [];
                            }

                            return $svc->rankAgentsByDistance($sig)
                                ->mapWithKeys(function ($item) {
                                    $label = "{$item['user']->prenom} {$item['user']->name}";

                                    if ($item['distance_km'] !== null) {
                                        $dist   = number_format($item['distance_km'], 1) . ' km';
                                        $source = $item['source'] === 'gps' ? '📍' : '🗺';
                                        $label .= " · {$source} {$dist}";
                                    }

                                    return [$item['user']->id => $label];
                                })
                                ->toArray();
                        })
                        ->hint(function (Get $get): string {
                            $sigId = $get('signalement_id');
                            if (! $sigId) {
                                return '';
                            }
                            $sig = Signalement::find($sigId);
                            if (! $sig?->latitude) {
                                return 'Signalement sans GPS — distances calculées depuis le centre de zone';
                            }

                            return '📍 GPS · 🗺 Zone centre — agent le plus proche pré-sélectionné';
                        })
                        ->hintColor(function (Get $get): string {
                            $sigId = $get('signalement_id');
                            if (! $sigId) {
                                return 'gray';
                            }

                            return Signalement::find($sigId)?->latitude ? 'success' : 'info';
                        })
                        ->searchable()
                        ->required(),

                    DateTimePicker::make('dateHeureAttribution')
                        ->label('Date et heure d\'attribution')
                        ->default(now())
                        ->required(),

                ])
                ->columns(2),
        ]);
    }
}

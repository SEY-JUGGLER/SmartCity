<?php

namespace App\Filament\Resources\Agent\Pages;

use App\Filament\Resources\Agent\AgentResource;
use App\Models\Attribution;
use App\Models\Evaluation;
use Filament\Actions\EditAction;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;

class ViewAgent extends ViewRecord
{
    protected static string $resource = AgentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informations personnelles')
                ->schema([
                    ImageEntry::make('photoProfi')
                        ->label('Photo')
                        ->disk('public')
                        ->circular()
                        ->placeholder('—')
                        ->columnSpanFull(),
                    TextEntry::make('prenom')
                        ->label('Prénom'),
                    TextEntry::make('name')
                        ->label('Nom'),
                    TextEntry::make('email')
                        ->label('Email'),
                    TextEntry::make('localite')
                        ->label('Localité')
                        ->placeholder('—'),
                    TextEntry::make('age')
                        ->label('Âge')
                        ->placeholder('—'),
                    TextEntry::make('zone.nomZone')
                        ->label('Zone')
                        ->badge()
                        ->color('info')
                        ->placeholder('—'),
                ])->columns(3),

            Section::make('Statut & Disponibilité')
                ->schema([
                    TextEntry::make('actif')
                        ->label('Compte actif')
                        ->badge()
                        ->color(fn ($state) => $state ? 'success' : 'danger')
                        ->formatStateUsing(fn ($state) => $state ? 'Actif' : 'Inactif'),
                    TextEntry::make('disponible')
                        ->label('Disponible')
                        ->badge()
                        ->color(fn ($state) => $state ? 'success' : 'warning')
                        ->formatStateUsing(fn ($state) => $state ? 'Disponible' : 'Indisponible'),
                    TextEntry::make('pointer')
                        ->label('Pointé aujourd\'hui')
                        ->badge()
                        ->color(fn ($state) => $state ? 'success' : 'danger')
                        ->formatStateUsing(fn ($state) => $state ? 'Pointé' : 'Absent'),
                    TextEntry::make('heurePointage')
                        ->label('Heure de pointage')
                        ->dateTime('H:i')
                        ->placeholder('—'),
                    TextEntry::make('compteBloque')
                        ->label('Compte bloqué')
                        ->badge()
                        ->color(fn ($state) => $state ? 'danger' : 'success')
                        ->formatStateUsing(fn ($state) => $state ? 'Bloqué' : 'Normal'),
                ])->columns(3),

            Section::make('Performances ce mois')
                ->schema([
                    TextEntry::make('missions_total')
                        ->label('Total missions')
                        ->state(fn ($record) => $record->attributionsAgent()->count())
                        ->badge()
                        ->color('primary'),
                    TextEntry::make('missions_mois')
                        ->label('Ce mois')
                        ->state(fn ($record) => $record->attributionsAgent()
                            ->whereMonth('dateHeureAttribution', now()->month)->count())
                        ->badge()
                        ->color('info'),
                    TextEntry::make('missions_terminees')
                        ->label('Terminées')
                        ->state(fn ($record) => $record->attributionsAgent()
                            ->whereHas('signalement', fn ($q) => $q->where('statut', 'terminer'))->count())
                        ->badge()
                        ->color('success'),
                    TextEntry::make('missions_en_cours')
                        ->label('En cours')
                        ->state(fn ($record) => $record->attributionsAgent()
                            ->whereHas('signalement', fn ($q) => $q->where('statut', 'enCours'))->count())
                        ->badge()
                        ->color('warning'),
                    TextEntry::make('temps_moyen')
                        ->label('Temps moyen (heures)')
                        ->state(fn ($record) => round(
                            $record->attributionsAgent()
                                ->whereHas('signalement', fn ($q) => $q->where('statut', 'terminer'))
                                ->join('signalements', 'attributions.signalement_id', '=', 'signalements.id')
                                ->whereNotNull('signalements.date_resolution')
                                ->selectRaw('COALESCE(AVG(' . \App\Helpers\DatabaseHelper::diffInHoursSql('signalements.created_at', 'signalements.date_resolution') . '), 0) as avg_hours')
                                ->value('avg_hours') ?? 0, 1
                        ))
                        ->badge()
                        ->color('gray'),
                    TextEntry::make('note_moyenne')
                        ->label('Note moyenne')
                        ->state(fn ($record) => round(
                            (function () use ($record) {
                                $evals = Evaluation::whereHas(
                                    'signalement.attribution',
                                    fn ($q) => $q->where('agent_id', $record->id)
                                )->get();
                                return $evals->count() > 0
                                    ? $evals->avg(fn ($e) => Evaluation::NOTE_SCORES[$e->note] ?? 0)
                                    : 0;
                            })(),
                            1
                        ))
                        ->badge()
                        ->color(fn ($state) => $state >= 4 ? 'success' : ($state >= 2 ? 'warning' : 'danger')),
                    TextEntry::make('taux_resolution')
                        ->label('Taux de résolution')
                        ->state(fn ($record) => $record->attributionsAgent()->count() === 0
                            ? '—'
                            : round(($record->attributionsAgent()->whereHas('signalement', fn ($q) => $q->where('statut', 'terminer'))->count() / $record->attributionsAgent()->count()) * 100) . '%')
                        ->badge()
                        ->color(fn ($state) => $state !== '—' ? ((intval($state) >= 80) ? 'success' : ((intval($state) >= 50) ? 'warning' : 'danger')) : 'gray'),
                ])->columns(6),
        ]);
    }
}

<?php

namespace App\Filament\Resources\Rapports\Pages;

use App\Filament\Resources\Rapports\RapportResource;
use App\Models\Rapport;
use App\Services\ActivityLoggerService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListRapports extends ListRecords
{
    protected static string $resource = RapportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generer_auto')
                ->label('Générer rapport du jour')
                ->icon('heroicon-o-arrow-path')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Générer un rapport automatique')
                ->modalDescription('Cela créera un rapport avec toutes les statistiques actuelles (période : début de mois → aujourd\'hui). Continuer ?')
                ->modalSubmitActionLabel('Générer')
                ->action(function () {
                    $stats = Rapport::calculerStats(
                        now()->startOfMonth()->toDateString(),
                        today()->toDateString()
                    );

                    $rapport = Rapport::create(array_merge($stats, [
                        'dateGeneration' => today(),
                        'date_debut'     => now()->startOfMonth()->toDate(),
                        'date_fin'       => today(),
                        'user_id'        => auth()->id(),
                    ]));

                    app(ActivityLoggerService::class)->logRapportGenerated($rapport);

                    Notification::make()
                        ->title('Rapport #' . $rapport->id . ' généré avec succès')
                        ->body('Du ' . $rapport->date_debut?->format('d/m/Y') . ' au ' . $rapport->date_fin?->format('d/m/Y'))
                        ->success()
                        ->send();

                    $this->redirect(RapportResource::getUrl('index'));
                }),

            CreateAction::make()
                ->label('Nouveau rapport personnalisé'),
        ];
    }
}

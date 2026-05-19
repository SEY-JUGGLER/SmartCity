<?php

namespace App\Filament\Resources\Rapports\Pages;

use App\Filament\Resources\Rapports\RapportResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRapport extends ViewRecord
{
    protected static string $resource = RapportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('imprimer')
                ->label('Imprimer / PDF')
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->url(fn () => route('rapport.print', $this->record))
                ->openUrlInNewTab(),

            EditAction::make(),
        ];
    }
}

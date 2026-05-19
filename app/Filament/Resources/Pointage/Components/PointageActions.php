<?php

namespace App\Filament\Resources\Pointage\Components;

use App\Models\User;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;

class PointageActions
{
    public static function make(): Action
    {
        return Action::make('pointage')
            ->label('Gérer le pointage')
            ->icon('heroicon-s-clock')
            ->color('primary')
            ->modalWidth('lg')
            ->modalSubmitActionLabel('Appliquer')
            ->modalCancelActionLabel('Annuler')
            ->fillForm(fn (User $record) => [
                'pointer'    => $record->pointer,
                'disponible' => $record->disponible,
            ])
            ->schema([
                Section::make('Action de pointage')
                    ->schema([
                        Toggle::make('pointer')
                            ->label('Marquer comme pointé'),
                        Toggle::make('disponible')
                            ->label('Définir comme disponible'),
                    ])->columns(2),
            ])
            ->action(function (User $record, array $data) {
                $update = [
                    'pointer'    => $data['pointer'] ?? false,
                    'disponible' => $data['disponible'] ?? false,
                ];
                if ($data['pointer'] && ! $record->pointer) {
                    $update['heurePointage'] = now();
                }
                $record->update($update);

                Notification::make()
                    ->title('Pointage mis à jour')
                    ->success()
                    ->send();
            });
    }

    public static function bulkPointageActions(): BulkAction
    {
        return BulkAction::make('bulk_pointage')
            ->label('Appliquer le pointage')
            ->icon('heroicon-s-clock')
            ->color('primary')
            ->modalWidth('lg')
            ->modalSubmitActionLabel('Appliquer')
            ->modalCancelActionLabel('Annuler')
            ->schema([
                Section::make('Action de pointage')
                    ->schema([
                        Toggle::make('pointer')
                            ->label('Marquer comme pointé'),
                        Toggle::make('disponible')
                            ->label('Définir comme disponible'),
                    ])->columns(2),
            ])
            ->action(function (Collection $records, array $data) {
                foreach ($records as $record) {
                    $update = [
                        'pointer'    => $data['pointer'] ?? false,
                        'disponible' => $data['disponible'] ?? false,
                    ];
                    if (($data['pointer'] ?? false) && ! $record->pointer) {
                        $update['heurePointage'] = now();
                    }
                    $record->update($update);
                }

                Notification::make()
                    ->title('Pointage mis à jour pour les agents sélectionnés')
                    ->success()
                    ->send();
            });
    }
}

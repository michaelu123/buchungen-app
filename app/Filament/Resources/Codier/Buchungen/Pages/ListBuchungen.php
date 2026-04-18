<?php

namespace App\Filament\Resources\Codier\Buchungen\Pages;

use App\Filament\Resources\BuchungenBase\Pages\ListBuchungenBase;
use App\Filament\Resources\Codier\Buchungen\BuchungResource;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;

class ListBuchungen extends ListBuchungenBase
{
    protected static string $resource = BuchungResource::class;

    public function selectOrt(): Action
    {
        return Action::make('selectOrt')
            ->label('Ort auswählen')
            //->hidden()
            ->schema(fn(array $arguments): array => [
                Select::make('url')
                    ->label('Bitte wähle den korrekten Ort aus:')
                    ->options($arguments['options'] ?? [])
                    ->required(),
            ])
            ->modalHeading('Ort nicht eindeutig')
            ->modalDescription('Es wurden mehrere Möglichkeiten gefunden. Bitte wähle eine aus:')
            ->modalSubmitActionLabel('EIN mit Auswahl laden')
            ->action(function (Action $action, $arguments, array $data): void {
                $record = $arguments["record"];
                $result = $record->fetchEIN($data['url']);

                if ($result['status'] === 'success') {
                    $record->update(['ein' => $result['ein']]);
                    Notification::make()
                        ->success()
                        ->title('EIN erfolgreich geladen: ' . $result['ein'])
                        ->send();
                    return;
                }

                Notification::make()
                    ->danger()
                    ->title('EIN laden fehlgeschlagen')
                    ->body($result['message'] ?? 'Unbekannter Fehler')
                    ->send();
            });
    }

}

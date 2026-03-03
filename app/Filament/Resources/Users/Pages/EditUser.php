<?php

namespace App\Filament\Resources\Users\Pages;

use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Filament\Forms\Components\TextInput;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use App\Filament\Resources\Users\UserResource;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getSavedNotificationTitle(): ?string
    {
        return "User updated successfully";
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('updatePassword')
                ->schema([
                    TextInput::make('password')
                        ->required()
                        ->password()
                        ->confirmed(),
                    TextInput::make('password_confirmation')
                        ->required()
                        ->password(),
                ])
                ->action(
                    function (array $data): void {
                        $this->record->update([
                            'password' => $data['password'],
                        ]);

                        Notification::make()
                            ->title('Password updated successfully')
                            ->success()
                            ->send();
                    }
                ),
            DeleteAction::make(),

        ];
    }
}

<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use UnitEnum;

class ManageMailAttachments extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-paper-clip';

    protected static string|UnitEnum|null $navigationGroup = 'Allgemein';

    protected static ?string $title = 'Mail-Anhänge verwalten';

    protected string $view = 'filament.pages.manage-mail-attachments';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'Codier' => $this->getFiles('Codier'),
            'RFSA' => $this->getFiles('RFSA'),
            'RFSF' => $this->getFiles('RFSF'),
            'RFSFP' => $this->getFiles('RFSFP'),
        ]);
    }

    public static function canAccess(): bool
    {
        $roles = Auth::user()->roles->map(fn($role) => $role["name"]);
        if ($roles->contains("ADMIN")) {
            return true;
        }
        return false;
    }

    protected function getFiles(string $category): array
    {
        return Storage::disk('local')->files("mail-attachments/{$category}");
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                $this->getCategorySection('Codier'),
                $this->getCategorySection('RFSA'),
                $this->getCategorySection('RFSF'),
                $this->getCategorySection('RFSFP'),
            ])
            ->statePath('data');
    }

    protected function getCategorySection(string $category): Section
    {
        return Section::make($category)
            ->schema([
                FileUpload::make($category)
                    ->label('Dateien')
                    ->multiple()
                    ->directory("mail-attachments/{$category}")
                    ->disk('local')
                    ->preserveFilenames()
                    ->reorderable()
                    ->downloadable()
                    ->openable(),
            ])
            ->collapsible();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Änderungen speichern')
                ->submit('submit'),
        ];
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        foreach (['Codier', 'RFSA', 'RFSF', 'RFSFP'] as $category) {
            $stateFiles = $data[$category] ?? [];
            $diskFiles = $this->getFiles($category);

            // Delete files that are on disk but not in state
            foreach ($diskFiles as $file) {
                if (!in_array($file, $stateFiles)) {
                    Storage::disk('local')->delete($file);
                }
            }
        }

        Notification::make()
            ->title('Anhänge gespeichert')
            ->success()
            ->send();

        // Refresh the form state
        $this->mount();
    }
}

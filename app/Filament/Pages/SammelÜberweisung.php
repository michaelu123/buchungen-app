<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Livewire\Attributes\On;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use UnitEnum;

class SammelÜberweisung extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-arrow-up';

    protected static ?string $title = 'Sammelüberweisung';

    protected static ?string $navigationLabel = 'Sammelüberweisung';

    protected static string|UnitEnum|null $navigationGroup = 'Allgemein';

    protected string $view = 'filament.pages.sammelüberweisung';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('xlsx')
                    ->label('XLSX-Datei auswählen oder hierher ziehen')
                    ->required()
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->mimeTypeMap([
                        '.xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ])
                    ->preserveFilenames()
                    ->storeFiles(false)
                    ->live()
                    ->afterStateUpdated(function ($livewire) {
                        $livewire->dispatch('process-upload');
                    })
            ])->statePath('data');
    }

    #[On('process-upload')]
    public function processUpload(): mixed
    {
        return $this->process();
    }

    public function process(): mixed
    {
        $this->form->validate();
        $data = $this->form->getState();
        $file = $data['xlsx'];

        if (!$file) {
            return null;
        }

        // In Filament 4/5, if multiple(false) is default, $file should be the object
        // if storeFiles(false) is used.

        // If it's an array (which can happen with some configurations)
        if (is_array($file)) {
            $file = array_values($file)[0] ?? null;
        }

        if (!$file || !($file instanceof TemporaryUploadedFile)) {
            return null;
        }

        $path = $file->getRealPath();
        $originalName = $file->getClientOriginalName();
        $fileName = pathinfo($originalName, PATHINFO_FILENAME);

        $content = file_get_contents($path);
        $transformedContent = $this->createEbics($content);
        unlink($path);
        unlink($path . ".json");

        $newName = $fileName . '_ebics.xml';

        // Use a temporary file path
        $tempPath = storage_path('app/temp/' . uniqid() . '_' . $newName);

        if (!is_dir(dirname($tempPath))) {
            mkdir(dirname($tempPath), 0755, true);
        }

        file_put_contents($tempPath, $transformedContent);

        $this->form->fill();

        \Filament\Notifications\Notification::make()
            ->title('Ebics-Datei erzeugt.')
            ->success()
            ->send();

        return response()->download($tempPath, $newName)->deleteFileAfterSend();
    }

    protected function createEbics(string $content): string
    {
        // Placeholder for transformation logic
        // For example, convert to uppercase
        return strtoupper($content);
    }
}

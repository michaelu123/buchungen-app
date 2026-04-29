<?php

namespace App\Filament\Pages;

use App\Filament\Resources\KurseBase\KursTableActions;
use App\Imports\SammelImport;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Livewire\Attributes\On;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Maatwebsite\Excel\Facades\Excel;
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

        if (!$file || !($file instanceof TemporaryUploadedFile)) {
            return null;
        }

        $path = $file->getRealPath();
        try {
            $originalName = $file->getClientOriginalName();
            $fileName = pathinfo($originalName, PATHINFO_FILENAME);

            $si = new SammelImport();
            Excel::import($si, $path);
            $ebicsXml = $this->createEbics($si->getList());
        } finally {
            unlink($path);
            unlink($path . ".json");
        }

        $newName = $fileName . '_ebics.xml';
        $this->form->fill();

        \Filament\Notifications\Notification::make()
            ->title('Ebics-Datei erzeugt.')
            ->success()
            ->send();

        return response()->streamDownload(function () use ($ebicsXml): void {
            echo $ebicsXml;
        }, $newName, ['Content-type' => 'application/xml']);
    }


    protected function createEbics(array $list): string
    {
        // TODO: factor out Ebics generation from KursTableActions
        $kta = new KursTableActions("", "", "", "");
        $xml = $kta->createXml($list, true);
        return $xml;
    }
}

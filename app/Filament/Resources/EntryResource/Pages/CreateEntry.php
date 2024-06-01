<?php

namespace App\Filament\Resources\EntryResource\Pages;

use App\Filament\Resources\EntryResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateEntry extends CreateRecord
{
    protected static string $resource = EntryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getSubheading(): ?string
    {
        return __('(*) fields are required.');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['assigned_by'] = auth()->id();

        return $data;
    }


    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('IRF Entry Saved Successfully!')
            ->body('Added record have been saved.')
            ->success()
            ->seconds(2)
            ->send();
    }
    protected function onValidationError(ValidationException $exception): void
    {
        Notification::make()
            ->title($exception->getMessage())
            ->danger()
            ->send();
    }
}

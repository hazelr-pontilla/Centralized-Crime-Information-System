<?php

namespace App\Filament\Resources\SuspectResource\Pages;

use App\Filament\Resources\SuspectResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateSuspect extends CreateRecord
{
    protected static string $resource = SuspectResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getSubheading(): ?string
    {
        return __('(*) fields are required.');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Suspect Created Successfully!')
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

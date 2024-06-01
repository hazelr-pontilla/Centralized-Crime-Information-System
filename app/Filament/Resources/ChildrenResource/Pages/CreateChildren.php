<?php

namespace App\Filament\Resources\ChildrenResource\Pages;

use App\Filament\Resources\ChildrenResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateChildren extends CreateRecord
{
    protected static string $resource = ChildrenResource::class;

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
            ->title('Children-in-Conflict Created Successfully!')
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

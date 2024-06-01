<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

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
            ->title('Role Created Successfully!')
            ->body('Added record have been saved.')
            ->success()
            ->seconds(2)
            ->send();
    }
}

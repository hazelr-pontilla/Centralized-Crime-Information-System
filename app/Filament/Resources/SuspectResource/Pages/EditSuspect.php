<?php

namespace App\Filament\Resources\SuspectResource\Pages;

use App\Filament\Resources\SuspectResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditSuspect extends EditRecord
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

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Client Dara Updated Successfully!')
            ->body('Updated record have been saved.')
            ->send()
            ->seconds(2)
            ->actions([
                Action::make('view')
                    ->button(),
            ]);

    }
}

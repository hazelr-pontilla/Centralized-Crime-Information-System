<?php

namespace App\Filament\Resources\ChildrenResource\Pages;

use App\Filament\Resources\ChildrenResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditChildren extends EditRecord
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

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Client Data Updated Successfully!')
            ->body('Updated record have been saved.')
            ->send()
            ->seconds(1)
            ->actions([
                Action::make('view')
                    ->button(),
            ]);

    }
}

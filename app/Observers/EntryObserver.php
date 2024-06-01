<?php

namespace App\Observers;

use App\Models\Entry;
use Filament\Notifications\Notification;

class EntryObserver
{
    /**
     * Handle the Entry "created" event.
     */
    public function created(Entry $entry): void
    {
        $superadmin = $entry->assignedTo;

        Notification::make()
            ->title('New IRF Entry has been created.')
            ->sendToDatabase($superadmin);
    }

    /**
     * Handle the Entry "updated" event.
     */
    public function updated(Entry $entry): void
    {
        $superadmin = $entry->assignedTo;

        Notification::make()
            ->title('IRF Entry has been updated.')
            ->sendToDatabase($superadmin);
    }

    /**
     * Handle the Entry "deleted" event.
     */
    public function deleted(Entry $entry): void
    {
        $superadmin = $entry->assignedTo;

        Notification::make()
            ->title('IRF Entry has been deleted.')
            ->sendToDatabase($superadmin);
    }

    /**
     * Handle the Entry "restored" event.
     */
    public function restored(Entry $entry): void
    {
        //
    }

    /**
     * Handle the Entry "force deleted" event.
     */
    public function forceDeleted(Entry $entry): void
    {
        //
    }
}

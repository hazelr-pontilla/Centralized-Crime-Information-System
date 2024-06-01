<?php

namespace App\Filament\Resources\SuspectResource\Pages;

use App\Filament\Resources\SuspectResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;

class ListSuspects extends ListRecords
{
    protected static string $resource = SuspectResource::class;

    protected ?string $heading = 'Suspects Data';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create New'),
        ];
    }

    protected ?string $subheading = 'This section display all the data on suspected individuals,
    like their names and actions related to a crime investigation.';
}

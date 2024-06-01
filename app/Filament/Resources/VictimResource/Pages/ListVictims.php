<?php

namespace App\Filament\Resources\VictimResource\Pages;

use App\Filament\Resources\VictimResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;

class ListVictims extends ListRecords
{
    protected static string $resource = VictimResource::class;

    protected ?string $heading = 'Victims Data';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create New'),
        ];
    }

    protected ?string $subheading = 'This section display all the data on victims individuals.';
}

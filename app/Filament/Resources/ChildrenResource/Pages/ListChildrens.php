<?php

namespace App\Filament\Resources\ChildrenResource\Pages;

use App\Filament\Resources\ChildrenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChildrens extends ListRecords
{
    protected static string $resource = ChildrenResource::class;

    protected ?string $heading = 'Minors Data';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create New'),
        ];
    }

    protected ?string $subheading = 'This section display all the data on children individuals under the age of 18 years old.';
}

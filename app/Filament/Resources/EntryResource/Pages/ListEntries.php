<?php

namespace App\Filament\Resources\EntryResource\Pages;

use App\Enums\EntryStatus;
use App\Filament\Resources\EntryResource;
use App\Models\Entry;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListEntries extends ListRecords
{
    protected static string $resource = EntryResource::class;

    protected ?string $heading = 'Clients Data';

    protected ?string $subheading = 'This section display all the data on client individuals.';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create New'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'All' => ListRecords\Tab::make(),
            'complete' => ListRecords\Tab::make('Complete Documents')
                ->icon('heroicon-m-check-circle')
                ->modifyQueryUsing(function ($query){
                    return $query->where('status', EntryStatus::COMPLETE->value);
                }),
            'incomplete' => ListRecords\Tab::make('Incomplete Documents')
                ->icon('heroicon-m-x-circle')
                ->modifyQueryUsing(function ($query){
                    return $query->where('status', EntryStatus::INCOMPLETE->value);
                }),
            'solved' => ListRecords\Tab::make('Solved Documents')
                ->icon('heroicon-m-archive-box')
                ->modifyQueryUsing(function ($query){
                    return $query->where('status', EntryStatus::SOLVED->value);
                }),


        ];

    }
}

<?php

namespace App\Filament\Resources\MasterProcesses\Pages;

use App\Filament\Resources\MasterProcesses\MasterProcessResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterProcesses extends ListRecords
{
    protected static string $resource = MasterProcessResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

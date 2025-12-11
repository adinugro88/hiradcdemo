<?php

namespace App\Filament\Resources\WorkProcesses\Pages;

use App\Filament\Resources\WorkProcesses\WorkProcessResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWorkProcesses extends ListRecords
{
    protected static string $resource = WorkProcessResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

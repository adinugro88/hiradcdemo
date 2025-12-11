<?php

namespace App\Filament\Resources\ProjectProcesses\Pages;

use App\Filament\Resources\ProjectProcesses\ProjectProcessResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProjectProcesses extends ListRecords
{
    protected static string $resource = ProjectProcessResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

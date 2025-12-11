<?php

namespace App\Filament\Resources\Hazards\Pages;

use App\Filament\Resources\Hazards\HazardResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHazards extends ListRecords
{
    protected static string $resource = HazardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

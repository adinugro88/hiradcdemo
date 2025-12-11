<?php

namespace App\Filament\Resources\ControlMeasures\Pages;

use App\Filament\Resources\ControlMeasures\ControlMeasureResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListControlMeasures extends ListRecords
{
    protected static string $resource = ControlMeasureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

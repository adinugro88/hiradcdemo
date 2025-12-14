<?php

namespace App\Filament\Resources\LiftingEquipments\Pages;

use App\Filament\Resources\LiftingEquipments\LiftingEquipmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLiftingEquipments extends ListRecords
{
    protected static string $resource = LiftingEquipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

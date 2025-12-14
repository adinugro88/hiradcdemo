<?php

namespace App\Filament\Resources\LiftingEquipments\Pages;

use App\Filament\Resources\LiftingEquipments\LiftingEquipmentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLiftingEquipment extends EditRecord
{
    protected static string $resource = LiftingEquipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

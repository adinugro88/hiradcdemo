<?php

namespace App\Filament\Resources\Hazards\Pages;

use App\Filament\Resources\Hazards\HazardResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHazard extends EditRecord
{
    protected static string $resource = HazardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

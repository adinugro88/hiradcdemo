<?php

namespace App\Filament\Resources\LiftingGears\Pages;

use App\Filament\Resources\LiftingGears\LiftingGearResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLiftingGear extends EditRecord
{
    protected static string $resource = LiftingGearResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

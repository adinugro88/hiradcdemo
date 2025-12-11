<?php

namespace App\Filament\Resources\ControlMeasures\Pages;

use App\Filament\Resources\ControlMeasures\ControlMeasureResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditControlMeasure extends EditRecord
{
    protected static string $resource = ControlMeasureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

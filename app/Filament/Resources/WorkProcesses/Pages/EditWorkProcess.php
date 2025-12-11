<?php

namespace App\Filament\Resources\WorkProcesses\Pages;

use App\Filament\Resources\WorkProcesses\WorkProcessResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWorkProcess extends EditRecord
{
    protected static string $resource = WorkProcessResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

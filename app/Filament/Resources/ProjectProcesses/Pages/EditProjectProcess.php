<?php

namespace App\Filament\Resources\ProjectProcesses\Pages;

use App\Filament\Resources\ProjectProcesses\ProjectProcessResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProjectProcess extends EditRecord
{
    protected static string $resource = ProjectProcessResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\MasterProcesses\Pages;

use App\Filament\Resources\MasterProcesses\MasterProcessResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterProcess extends EditRecord
{
    protected static string $resource = MasterProcessResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

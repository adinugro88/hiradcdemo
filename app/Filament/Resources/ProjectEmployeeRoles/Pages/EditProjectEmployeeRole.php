<?php

namespace App\Filament\Resources\ProjectEmployeeRoles\Pages;

use App\Filament\Resources\ProjectEmployeeRoles\ProjectEmployeeRoleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProjectEmployeeRole extends EditRecord
{
    protected static string $resource = ProjectEmployeeRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\ProjectEmployeeRoles\Pages;

use App\Filament\Resources\ProjectEmployeeRoles\ProjectEmployeeRoleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProjectEmployeeRoles extends ListRecords
{
    protected static string $resource = ProjectEmployeeRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

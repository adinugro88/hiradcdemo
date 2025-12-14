<?php

namespace App\Filament\Resources\LiftingGears\Pages;

use App\Filament\Resources\LiftingGears\LiftingGearResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLiftingGears extends ListRecords
{
    protected static string $resource = LiftingGearResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

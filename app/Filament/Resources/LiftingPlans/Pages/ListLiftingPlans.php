<?php

namespace App\Filament\Resources\LiftingPlans\Pages;

use App\Filament\Resources\LiftingPlans\LiftingPlanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLiftingPlans extends ListRecords
{
    protected static string $resource = LiftingPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

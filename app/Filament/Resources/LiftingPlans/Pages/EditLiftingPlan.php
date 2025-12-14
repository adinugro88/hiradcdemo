<?php

namespace App\Filament\Resources\LiftingPlans\Pages;

use App\Filament\Resources\LiftingPlans\LiftingPlanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLiftingPlan extends EditRecord
{
    protected static string $resource = LiftingPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

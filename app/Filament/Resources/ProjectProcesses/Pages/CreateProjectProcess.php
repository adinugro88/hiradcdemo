<?php

namespace App\Filament\Resources\ProjectProcesses\Pages;

use App\Filament\Resources\ProjectProcesses\ProjectProcessResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateProjectProcess extends CreateRecord
{
    protected static string $resource = ProjectProcessResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $projectProcess = static::getModel()::create([
            'project_id' => $data['project_id'],
            'process'    => $data['process'],
        ]);

        // =====================
        // RISKS
        // =====================
        foreach ($data['risks'] ?? [] as $risk) {
            $projectProcess->risks()->create($risk);
        }

        // =====================
        // CONTROL RISKS
        // =====================
        foreach ($data['control_risks'] ?? [] as $control) {
            $projectProcess->controlRisks()->create($control);
        }

        // =====================
        // REGULATIONS (PIVOT)
        // =====================
        if (! empty($data['regulations'])) {
            $projectProcess->regulations()->sync($data['regulations']);
        }

        return $projectProcess;
    }
}

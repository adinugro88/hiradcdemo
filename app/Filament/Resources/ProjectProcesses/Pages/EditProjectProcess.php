<?php

namespace App\Filament\Resources\ProjectProcesses\Pages;

use App\Filament\Resources\ProjectProcesses\ProjectProcessResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EditProjectProcess extends EditRecord
{
    protected static string $resource = ProjectProcessResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * PRELOAD DATA (GET)
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // WORKS
        $data['works'] = $this->record
            ->works()
            ->pluck('works.id')
            ->toArray();

        // RISKS
        $data['risks'] = $this->record->risks
            ->map(fn($risk) => [
                'risk_assessment_id' => $risk->risk_assessment_id,
                'probability'        => $risk->probability,
                'severity'           => $risk->severity,
                'total_value'        => $risk->total_value,
                'category'           => $risk->category,
            ])
            ->toArray();

        // CONTROL RISKS
        $data['control_risks'] = $this->record->controlRisks
            ->map(fn($control) => [
                'control_measures_id' => $control->control_measures_id,
                'probability'         => $control->probability,
                'severity'            => $control->severity,
                'total_value'         => $control->total_value,
                'category'            => $control->category,
            ])
            ->toArray();

        // REGULATIONS
        $data['regulations'] = $this->record
            ->regulations()
            ->pluck('regulations.id')
            ->toArray();

        return $data;
    }

    /**
     * SAVE DATA (POST)
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // dd(json_decode($data['_risk_control_data'], true));
        DB::transaction(function () use ($record, $data) {

            // MAIN
            $record->update([
                'project_id' => $data['project_id'],
                'process'    => $data['process'],
            ]);

            // WORKS (pivot work_processes)
            if (isset($data['works'])) {
                $record->works()->sync($data['works']);
            }

            // CLEAR OLD DATA
            $record->risks()->delete();
            $record->controlRisks()->delete();

            // HANDLE TREE DATA
            if (!empty($data['_risk_control_data'])) {
                $riskControlData = is_string($data['_risk_control_data'])
                    ? json_decode($data['_risk_control_data'], true)
                    : $data['_risk_control_data'];

                if (is_array($riskControlData)) {

                    // RISKS
                    foreach (($riskControlData['risks'] ?? []) as $riskId => $riskData) {
                        if (!($riskData['checked'] ?? false)) continue;

                        $record->risks()->create([
                            'risk_assessment_id' => $riskId,
                            'probability' => $riskData['probability'] ?? 0,
                            'severity' => $riskData['severity'] ?? 0,
                            'total_value' => $riskData['total'] ?? 0,
                            'category' => $riskData['category'] ?? 'Very Low',
                        ]);
                    }

                    // CONTROL RISKS
                    foreach (($riskControlData['controls'] ?? []) as $controlId => $controlData) {
                        if (!($controlData['checked'] ?? false)) continue;

                        $record->controlRisks()->create([
                            'control_measures_id' => $controlId,
                            'probability' => $controlData['probability'] ?? 0,
                            'severity' => $controlData['severity'] ?? 0,
                            'total_value' => $controlData['total'] ?? 0,
                            'category' => $controlData['category'] ?? 'Very Low',
                        ]);
                    }
                }
            }

            // REGULATIONS
            $record->regulations()->sync($data['regulations'] ?? []);
        });

        return $record;
    }
}

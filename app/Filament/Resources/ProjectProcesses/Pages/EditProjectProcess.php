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
        // Build _risk_control_data structure
        $riskControlData = [
            'works' => [],
            'risks' => [],
            'controls' => [],
        ];

        // WORKS
        foreach ($this->record->works as $work) {
            $riskControlData['works'][$work->id] = true;
        }

        // RISKS
        foreach ($this->record->risks as $risk) {
            $riskControlData['risks'][$risk->risk_assessment_id] = [
                'checked' => true,
                'probability' => $risk->probability,
                'severity' => $risk->severity,
                'total' => $risk->total_value,
                'category' => $risk->category,
            ];
        }

        // CONTROL RISKS
        foreach ($this->record->controlRisks as $control) {
            $riskControlData['controls'][$control->control_measures_id] = [
                'checked' => true,
                'probability' => $control->probability,
                'severity' => $control->severity,
                'total' => $control->total_value,
                'category' => $control->category,
            ];
        }

        // INFER HAZARDS (UI State)
        // If a risk or control is checked, its parent hazard should be checked.
        // We need to look up hazards for these risks/controls.
        // Since we don't have direct Hazard ID in Risk/ControlRisk models (only via relationships),
        // we can either load them or just iterate the Works->Hazards->Risks structure if we had it.
        // But here we only have the saved records.
        
        // Strategy: Iterate through ALL works (loaded for the form) and check if they have selected risks.
        // However, standard approach:
        
        $riskIds = array_keys($riskControlData['risks']);
        $controlIds = array_keys($riskControlData['controls']);
        
        if (!empty($riskIds) || !empty($controlIds)) {
            // Find hazards for these risks
            $relatedHazards = \App\Models\Hazard::whereHas('riskAssessments', function($q) use ($riskIds) {
                $q->whereIn('id', $riskIds);
            })->orWhereHas('controlMeasures', function($q) use ($controlIds) {
                $q->whereIn('id', $controlIds);
            })->pluck('id');
            
            foreach ($relatedHazards as $hazardId) {
                $riskControlData['hazards'][$hazardId] = true;
            }
        }

        $data['_risk_control_data'] = json_encode($riskControlData);

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
            // MAIN
            $mainData = [];
            if (isset($data['project_id'])) $mainData['project_id'] = $data['project_id'];
            if (array_key_exists('process', $data)) $mainData['process'] = $data['process'];
            if (array_key_exists('prepared_by', $data)) $mainData['prepared_by'] = $data['prepared_by'];
            if (array_key_exists('checked_by', $data)) $mainData['checked_by'] = $data['checked_by'];
            if (array_key_exists('approved_by', $data)) $mainData['approved_by'] = $data['approved_by'];
            if (array_key_exists('acknowledged_by', $data)) $mainData['acknowledged_by'] = $data['acknowledged_by'];
            
            if (!empty($mainData)) {
                $record->update($mainData);
            }

            // HANDLE TREE DATA
            if (!empty($data['_risk_control_data'])) {
                $riskControlData = is_string($data['_risk_control_data'])
                    ? json_decode($data['_risk_control_data'], true)
                    : $data['_risk_control_data'];

                if (is_array($riskControlData)) {

                    // WORKS
                    $worksIds = [];
                    foreach (($riskControlData['works'] ?? []) as $workId => $isChecked) {
                         if ($isChecked) {
                             $worksIds[] = $workId;
                         }
                    }
                    $record->works()->sync($worksIds);

                    // CLEAR OLD DATA
                    $record->risks()->delete();
                    $record->controlRisks()->delete();

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

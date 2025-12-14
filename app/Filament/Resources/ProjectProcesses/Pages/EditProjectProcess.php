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
            ->map(fn ($risk) => [
                'risk_assessment_id' => $risk->risk_assessment_id,
                'probability'        => $risk->probability,
                'severity'           => $risk->severity,
                'total_value'        => $risk->total_value,
                'category'           => $risk->category,
            ])
            ->toArray();

        // CONTROL RISKS
        $data['control_risks'] = $this->record->controlRisks
            ->map(fn ($control) => [
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
        DB::transaction(function () use ($record, $data) {

            // MAIN
            $record->update([
                'project_id' => $data['project_id'],
                'process'    => $data['process'],
            ]);

            // WORKS
            if (isset($data['works'])) {
                $record->works()->sync($data['works']);
            }

            // RISKS
            $record->risks()->delete();
            foreach ($data['risks'] ?? [] as $risk) {
                $record->risks()->create($risk);
            }

            // CONTROL RISKS
            $record->controlRisks()->delete();
            foreach ($data['control_risks'] ?? [] as $control) {
                $record->controlRisks()->create($control);
            }

            // REGULATIONS
            $record->regulations()->sync($data['regulations'] ?? []);
        });

        return $record;
    }
}

<?php

namespace App\Filament\Resources\JsaResource\Pages;

use App\Filament\Resources\JsaResource;
use App\Models\Project;
use App\Models\Work;
use App\Models\Hazard;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;

class CreateJsa extends CreateRecord
{
    protected static string $resource = JsaResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('project_id')
                    ->label('Nama Proyek')
                    ->options(Project::pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->live(),

                Select::make('work_id')
                    ->label('Pilih Pekerjaan')
                    ->options(function (callable $get) {
                        $projectId = $get('project_id');
                        if (!$projectId) return [];
                        return Work::whereHas('projectProcesses', function ($q) use ($projectId) {
                            $q->where('project_id', $projectId);
                        })->pluck('name', 'id');
                    })
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (callable $set) => $set('selected_hazard_ids', [])),

                CheckboxList::make('selected_hazard_ids')
                    ->label('Pilih Hazard yang Ingin Dimasukkan ke JSA')
                    ->options(function (callable $get) {
                        $workId = $get('work_id');
                        if (!$workId) return [];
                        return Hazard::where('work_id', $workId)
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->columns(2)
                    ->live(),

                Fieldset::make('Preview JSA Steps')
                    ->schema([
                        Repeater::make('preview')
                            ->schema([
                                Textarea::make('work_sequence')->disabled(),
                                Textarea::make('risk_analysis')->disabled()->rows(2),
                                Textarea::make('risk_control')->disabled()->rows(2),
                            ])
                            ->addable(false)
                            ->deletable(false)
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (callable $get) => !empty($get('selected_hazard_ids'))),

                DatePicker::make('created_date')
                    ->label('Tanggal Pembuatan')
                    ->required()
                    ->default(now()),
            ]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $workId = $data['work_id'];
        $selectedHazardIds = $data['selected_hazard_ids'] ?? [];

        $work = Work::find($workId);
        if (!$work) return $data;

        // Buat JSA utama
        $jsa = \App\Models\Jsa::create([
            'project_id' => $data['project_id'],
            'job_name' => $work->name,
            'created_date' => $data['created_date'],
        ]);

        // Buat steps berdasarkan hazard yang dipilih
        foreach ($selectedHazardIds as $i => $hazardId) {
            $hazard = Hazard::with(['riskAssessments', 'controlMeasures'])->find($hazardId);
            if (!$hazard) continue;

            $ra = $hazard->riskAssessments->first();
            $cm = $hazard->controlMeasures;

            $riskAnalysis = "{$hazard->name}: {$ra?->description}\n";
            $riskAnalysis .= "(Prob: {$ra?->probability_before}, Sev: {$ra?->severity_before}, Total: {$ra?->total_before}, Kategori: {$ra?->category_before})";

            $riskControl = "Basic: {$cm?->basic_measure}\n";
            $riskControl .= "Opportunity: {$cm?->opportunity_measure}\n";
            $riskControl .= "Advanced: {$cm?->advanced_measure}";

            \App\Models\JsaStep::create([
                'jsa_id' => $jsa->id,
                'step_number' => $i + 1,
                'work_sequence' => $work->name,
                'risk_analysis' => trim($riskAnalysis),
                'risk_control' => trim($riskControl),
                'pic' => null,
                'target_date' => null,
            ]);
        }

        return ['id' => $jsa->id];
    }
}
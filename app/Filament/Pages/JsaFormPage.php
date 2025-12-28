<?php

namespace App\Filament\Pages;

use App\Models\Jsa;
use App\Models\JsaStep;
use App\Models\User;
use App\Models\Work;
use App\Models\Project;
use App\Models\ProjectProcess;
use App\Models\WorkProcess;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;

class JsaFormPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $title = 'Form JSA';
    protected static ?string $slug = 'jsa/form';
    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'filament.pages.jsa-form-page';

    public array $data = [];
    public ?int $editingIndex = null;
    public ?Jsa $record = null;

    public function mount(): void
    {
        $recordId = request()->query('record');

        if ($recordId) {
            $this->record = Jsa::with('steps')->findOrFail($recordId);

            $this->data = [
                'project_name'       => $this->record->project_name,
                'job_name'           => $this->record->job_name,
                'created_date'       => $this->record->created_date,
                'supervisor_id'      => $this->record->supervisor_id,
                'site_manager_id'    => $this->record->site_manager_id,
                'leader_hse_id'      => $this->record->leader_hse_id,
                'project_manager_id' => $this->record->project_manager_id,
                'steps'              => $this->record->steps->map(function ($step) {
                    return [
                        'work_sequence' => $step->work_sequence,
                        'risk_analysis' => $step->risk_analysis,
                        'risk_control'  => $step->risk_control,
                        'pic'           => $step->pic,
                        'target_date'   => $step->target_date,
                    ];
                })->toArray(),
            ];
        }

        $this->form->fill($this->data);
    }

    public function updateStepData(int $index, array $data): void
    {
        $steps = $this->data['steps'] ?? [];

        if (! isset($steps[$index])) {
            return;
        }

        $steps[$index]['work_sequence'] = $data['work_sequence'] ?? $steps[$index]['work_sequence'];
        $steps[$index]['risk_analysis'] = $data['risk_analysis'] ?? $steps[$index]['risk_analysis'];
        $steps[$index]['risk_control']  = $data['risk_control'] ?? $steps[$index]['risk_control'];

        $this->data['steps'] = $steps;
        $this->form->fill($this->data);

        Notification::make()
            ->title('Data pekerjaan berhasil diupdate')
            ->success()
            ->send();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->schema([
                Section::make('Data Utama')
                    ->headerActions([
                        Action::make('ambilTemplateHiradc')
                            ->label('Ambil dari HIRADC')
                            ->icon('heroicon-o-list-bullet')
                            ->form([
                                Select::make('project_id')
                                    ->label('Pilih Project')
                                    ->options(
                                        Project::query()
                                            ->orderBy('name')
                                            ->pluck('name', 'id')
                                    )
                                    ->searchable()
                                    ->required()
                                    ->live(),

                                Select::make('project_process_id')
                                    ->label('Pilih Proses / Pekerjaan')
                                    ->options(function ($get) {
                                        $projectId = $get('project_id');

                                        if (! $projectId) {
                                            return [];
                                        }

                                        return ProjectProcess::query()
                                            ->where('project_id', $projectId)
                                            ->orderBy('process')
                                            ->pluck('process', 'id');
                                    })
                                    ->searchable()
                                    ->required(),
                            ])
                            ->action(function (array $data): void {
                                $steps            = $this->data['steps'] ?? [];
                                $projectId        = $data['project_id'] ?? null;
                                $projectProcessId = $data['project_process_id'] ?? null;

                                if (! $projectId || ! $projectProcessId) {
                                    return;
                                }

                                $project = Project::find($projectId);
                                $process = ProjectProcess::find($projectProcessId);

                                if ($project) {
                                    $this->data['project_name'] = $project->name;
                                }

                                if ($process) {
                                    // gunakan kolom `process` sebagai nama pekerjaan
                                    $this->data['job_name'] = $process->process;
                                }

                                // Cari semua work yang terkait dengan proses ini (work_processes)
                                $workIds = WorkProcess::query()
                                    ->where('project_process_id', $projectProcessId)
                                    ->pluck('work_id');

                                if ($workIds->isEmpty()) {
                                    $this->data['steps'] = $steps;
                                    $this->form->fill($this->data);

                                    Notification::make()
                                        ->title('Tidak ada pekerjaan yang terhubung ke proses ini')
                                        ->warning()
                                        ->send();

                                    return;
                                }

                                $works = Work::query()
                                    ->whereIn('id', $workIds)
                                    ->with([
                                        'hazards' => function ($query) {
                                            $query->with([
                                                'riskAssessments',
                                                'controlMeasures' => function ($subQuery) {
                                                    $subQuery->select('id', 'hazard_id', 'basic_measure')
                                                        ->whereNotNull('basic_measure')
                                                        ->where('basic_measure', '!=', '');
                                                },
                                            ]);
                                        },
                                    ])
                                    ->orderBy('name')
                                    ->get();

                                foreach ($works as $work) {
                                    foreach ($work->hazards as $hazard) {
                                        foreach ($hazard->riskAssessments as $risk) {
                                            $riskText = trim($hazard->name . ' - ' . $risk->description);

                                            foreach ($hazard->controlMeasures as $cm) {
                                                $controlText = $cm->basic_measure;

                                                if (! empty($controlText)) {
                                                    $steps[] = [
                                                        'work_sequence' => $work->name,
                                                        'risk_analysis' => $riskText,
                                                        'risk_control'  => $controlText,
                                                        'pic'           => null,
                                                        'target_date'   => null,
                                                    ];
                                                }
                                            }
                                        }
                                    }
                                }

                                $this->data['steps'] = $steps;
                                $this->form->fill($this->data);

                                Notification::make()
                                    ->title('Data berhasil diambil dari HIRADC')
                                    ->success()
                                    ->send();
                            })
                            ->modalHeading('Ambil Template dari HIRADC per Project & Proses')
                            ->modalSubmitActionLabel('Masukkan ke Tabel'),
                    ])
                    ->schema([
                        TextInput::make('project_name')
                            ->label('Nama Proyek')
                            ->required()
                            ->columnSpan(1),
                        TextInput::make('job_name')
                            ->label('Nama Pekerjaan')
                            ->required()
                            ->columnSpan(1),
                        DatePicker::make('created_date')
                            ->label('Tanggal Pembuatan')
                            ->required()
                            ->columnSpan(1),
                        Placeholder::make('header_spacer_left')
                            ->content('')
                            ->columnSpan(1),
                        Placeholder::make('header_spacer_mid')
                            ->content('')
                            ->columnSpan(1),
                        Placeholder::make('header_spacer_right')
                            ->content('')
                            ->columnSpan(1),
                        Select::make('supervisor_id')
                            ->label('Supervisor')
                            ->options(User::query()->orderBy('name')->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->columnSpan(1.5),
                        Select::make('site_manager_id')
                            ->label('Site Manager')
                            ->options(User::query()->orderBy('name')->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->columnSpan(1.5),
                        Select::make('leader_hse_id')
                            ->label('Leader HSE Proyek')
                            ->options(User::query()->orderBy('name')->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->columnSpan(1.5),
                        Select::make('project_manager_id')
                            ->label('Project Manager')
                            ->options(User::query()->orderBy('name')->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->columnSpan(1.5),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),

                Section::make('Analisa Risiko Pekerjaan')
                    ->headerActions([
                        Action::make('tambahBarisManual')
                            ->label('Tambah baris manual')
                            ->icon('heroicon-o-plus')
                            ->form([
                                TextInput::make('work_sequence')
                                    ->label('Jenis Pekerjaan')
                                    ->required(),
                                TextInput::make('hazard')
                                    ->label('Hazard-Risk')
                                    ->required(),
                                Textarea::make('control_measure')
                                    ->label('Control Measure')
                                    ->rows(3)
                                    ->required(),
                            ])
                            ->action(function (array $data): void {
                                $steps = $this->data['steps'] ?? [];

                                $steps[] = [
                                    'work_sequence' => $data['work_sequence'] ?? null,
                                    'risk_analysis' => trim($data['hazard'] ?? ''),
                                    'risk_control'  => $data['control_measure'] ?? null,
                                    'pic'           => null,
                                    'target_date'   => null,
                                ];

                                $this->data['steps'] = $steps;
                                $this->form->fill($this->data);

                                Notification::make()
                                    ->title('Baris manual berhasil ditambahkan')
                                    ->success()
                                    ->send();
                            })
                            ->modalHeading('Tambah Baris Manual'),
                    ])
                    ->schema([
                        Placeholder::make('risiko_table')
                            ->label('')
                            ->content(function () {
                                $steps = $this->data['steps'] ?? [];

                                return view('components.jsa-risiko-table', [
                                    'steps' => $steps,
                                ]);
                            })
                            ->columnSpanFull(),
                    ])
                    ->compact()
                    ->columnSpanFull(),
            ]);
    }

    public function deleteStep(int $index): void
    {
        $steps = $this->data['steps'] ?? [];

        if (! isset($steps[$index])) {
            return;
        }

        unset($steps[$index]);
        $this->data['steps'] = array_values($steps);
        $this->form->fill($this->data);

        Notification::make()
            ->title('Baris dihapus')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function submit(): void
    {
        DB::transaction(function () {
            if ($this->record) {
                $this->record->update([
                    'project_name'       => $this->data['project_name'] ?? null,
                    'job_name'           => $this->data['job_name'] ?? null,
                    'created_date'       => $this->data['created_date'] ?? null,
                    'supervisor_id'      => $this->data['supervisor_id'] ?? null,
                    'site_manager_id'    => $this->data['site_manager_id'] ?? null,
                    'leader_hse_id'      => $this->data['leader_hse_id'] ?? null,
                    'project_manager_id' => $this->data['project_manager_id'] ?? null,
                ]);

                $this->record->steps()->delete();

                $jsa = $this->record;
            } else {
                $jsa = Jsa::create([
                    'project_name'       => $this->data['project_name'] ?? null,
                    'job_name'           => $this->data['job_name'] ?? null,
                    'created_date'       => $this->data['created_date'] ?? null,
                    'supervisor_id'      => $this->data['supervisor_id'] ?? null,
                    'site_manager_id'    => $this->data['site_manager_id'] ?? null,
                    'leader_hse_id'      => $this->data['leader_hse_id'] ?? null,
                    'project_manager_id' => $this->data['project_manager_id'] ?? null,
                ]);
            }

            $steps = array_values($this->data['steps'] ?? []);

            foreach ($steps as $i => $step) {
                JsaStep::create([
                    'jsa_id'        => $jsa->id,
                    'step_number'   => $i + 1,
                    'work_sequence' => $step['work_sequence'] ?? null,
                    'risk_analysis' => $step['risk_analysis'] ?? null,
                    'risk_control'  => $step['risk_control'] ?? null,
                    'pic'           => $step['pic'] ?? null,
                    'target_date'   => $step['target_date'] ?? null,
                ]);
            }
        });

        Notification::make()
            ->title($this->record ? 'JSA berhasil diupdate' : 'JSA berhasil disimpan')
            ->success()
            ->send();

        $this->redirect(JsaIndexPage::getUrl());
    }
}

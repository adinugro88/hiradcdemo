<?php

namespace App\Filament\Pages;

use App\Models\Jsa;
use App\Models\JsaStep;
use App\Models\User;
use App\Models\Work;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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

    protected function getActions(): array
    {
        /** @var Jsa|null $jsa */
        $jsa = $this->record ?? null;

        $url = $jsa ? route('jsa.pdf', ['jsa' => $jsa->id]) : null;

        return [
            Action::make('exportPdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document-text')
                ->url($url)
                ->openUrlInNewTab(),
        ];
    }

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

    // Update step data
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

    /**
     * Ambil template HIRADC dengan grouping:
     * - 1 baris untuk 1 kombinasi hazard+risk
     * - risk_control = gabungan semua basic_measure (dipisah "; ")
     *
     * Catatan:
     * - Karena key-nya hazard+risk, work yang berbeda tapi hazard+risk sama akan digabung.
     */
    private function buildStepsFromHiradcGrouped(): array
    {
        $works = Work::query()
            ->with([
                'hazards' => function ($q) {
                    $q->with([
                        'riskAssessments',
                        'controlMeasures' => function ($cmq) {


                            $cmq->select('id', 'hazard_id', 'basic_measure')
                                ->whereNotNull('basic_measure')
                                ->where('basic_measure', '!=', '');
                        },
                    ]);
                },
            ])
            ->orderBy('name')
            ->get();

        $grouped = [];

        foreach ($works as $work) {
            foreach ($work->hazards as $hazard) {

                // Semua control measure hazard ini (unik)
                $measures = $hazard->controlMeasures
                    ->pluck('basic_measure')
                    ->map(fn($v) => trim((string) $v))
                    ->filter(fn($v) => $v !== '')
                    ->unique()
                    ->values()
                    ->all();

                foreach ($hazard->riskAssessments as $risk) {
                    $riskText = trim($hazard->name . ' - ' . $risk->description);

                    // ✅ grouping hanya hazard+risk (sesuai request)
                    $key = $hazard->id . '|' . $risk->id;

                    if (! isset($grouped[$key])) {
                        $grouped[$key] = [
                            // work_sequence akan diisi dari work pertama yang ketemu
                            'work_sequence' => $work->name,
                            'risk_analysis' => $riskText,
                            'risk_control'  => '',
                            'pic'           => null,
                            'target_date'   => null,
                        ];
                    }

                    // merge measures ke risk_control
                    $existing = collect(explode(';', (string) $grouped[$key]['risk_control']))
                        ->map(fn($v) => trim($v))
                        ->filter(fn($v) => $v !== '');

                    $merged = $existing
                        ->merge($measures)
                        ->map(fn($v) => trim((string) $v))
                        ->filter(fn($v) => $v !== '')
                        ->unique()
                        ->values()
                        ->all();

                    $grouped[$key]['risk_control'] = implode('; ', $merged);
                }
            }
        }

        // buang yang risk_control kosong
        $steps = array_values(array_filter($grouped, fn($row) => ! empty($row['risk_control'])));

        return $steps;
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
                                        \App\Models\Project::query()
                                            ->orderBy('name')
                                            ->pluck('name', 'id')
                                    )
                                    ->searchable()
                                    ->required(),
                            ])
                            ->action(function (array $data): void {
                                $projectId = $data['project_id'] ?? null;
                                if (! $projectId) return;

                                $project = \App\Models\Project::find($projectId);
                                if ($project && empty($this->data['project_name'])) {
                                    $this->data['project_name'] = $project->name;
                                }

                                // ✅ Replace steps dengan hasil grouping
                                $this->data['steps'] = $this->buildStepsFromHiradcGrouped();
                                $this->form->fill($this->data);

                                Notification::make()
                                    ->title('Data berhasil diambil dari HIRADC (group per Hazard - Risk)')
                                    ->success()
                                    ->send();
                            })
                            ->modalHeading('Ambil Template dari HIRADC per Project')
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
                        Action::make('ambilTemplateHiradc')
                            ->label('Ambil dari HIRADC')
                            ->icon('heroicon-o-list-bullet')
                            ->form([
                                Select::make('project_id')
                                    ->label('Pilih Project')
                                    ->options(
                                        \App\Models\Project::query()
                                            ->orderBy('name')
                                            ->pluck('name', 'id')
                                    )
                                    ->searchable()
                                    ->required(),
                            ])
                            ->action(function (array $data): void {
                                $projectId = $data['project_id'] ?? null;
                                if (! $projectId) return;

                                $project = \App\Models\Project::find($projectId);
                                if ($project && empty($this->data['project_name'])) {
                                    $this->data['project_name'] = $project->name;
                                }

                                // ✅ Replace steps dengan hasil grouping
                                $this->data['steps'] = $this->buildStepsFromHiradcGrouped();
                                $this->form->fill($this->data);

                                Notification::make()
                                    ->title('Data berhasil diambil dari HIRADC (group per Hazard - Risk)')
                                    ->success()
                                    ->send();
                            })
                            ->modalHeading('Ambil Template dari HIRADC per Project')
                            ->modalSubmitActionLabel('Masukkan ke Tabel'),
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

    // Hapus baris
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
                // Update existing JSA
                $this->record->update([
                    'project_name'       => $this->data['project_name'] ?? null,
                    'job_name'           => $this->data['job_name'] ?? null,
                    'created_date'       => $this->data['created_date'] ?? null,
                    'supervisor_id'      => $this->data['supervisor_id'] ?? null,
                    'site_manager_id'    => $this->data['site_manager_id'] ?? null,
                    'leader_hse_id'      => $this->data['leader_hse_id'] ?? null,
                    'project_manager_id' => $this->data['project_manager_id'] ?? null,
                ]);

                // Delete existing steps
                $this->record->steps()->delete();

                $jsa = $this->record;
            } else {
                // Create new JSA
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

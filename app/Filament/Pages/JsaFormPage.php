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

    public function mount(): void
    {
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
                                $steps = $this->data['steps'] ?? [];

                                $projectId = $data['project_id'] ?? null;
                                if (! $projectId) {
                                    return;
                                }

                                $project = \App\Models\Project::find($projectId);
                                if ($project && empty($this->data['project_name'])) {
                                    $this->data['project_name'] = $project->name;
                                }

                                // Ambil data dengan relationship
                                $works = Work::query()
                                    ->with('hazards.riskAssessments.controlMeasures')
                                    ->orderBy('name')
                                    ->get();

                                foreach ($works as $work) {
                                    foreach ($work->hazards as $hazard) {
                                        // Loop setiap risk assessment
                                        foreach ($hazard->riskAssessments as $risk) {
                                            $riskText = trim($hazard->name.' - '.$risk->description);

                                            // Loop setiap control measure
                                            foreach ($hazard->controlMeasures as $cm) {
                                                // Ambil basic_measure jika ada (tidak kosong)
                                                $controlText = $cm->basic_measure ?? null;

                                                // Hanya tambah ke steps jika ada basic_measure
                                                if (! empty($controlText)) {
                                                    $steps[] = [
                                                        'work_sequence' => $work->name,
                                                        'risk_analysis' => $riskText,
                                                        'risk_control'  => $controlText, // ✅ Hanya basic_measure
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
                        Action::make('tambahRisikoManual')
                            ->label('Tambah Risiko Pekerjaan')
                            ->icon('heroicon-o-plus-circle')
                            ->form([
                                TextInput::make('work_sequence')
                                    ->label('Jenis Pekerjaan')
                                    ->required(),
                                Textarea::make('risk_analysis')
                                    ->label('Hazard - Risk')
                                    ->rows(3)
                                    ->required(),
                                Textarea::make('risk_control')
                                    ->label('Control Measure')
                                    ->rows(3)
                                    ->required(),
                            ])
                            ->action(function (array $data): void {
                                $steps   = $this->data['steps'] ?? [];
                                $steps[] = [
                                    'work_sequence' => $data['work_sequence'],
                                    'risk_analysis' => $data['risk_analysis'],
                                    'risk_control'  => $data['risk_control'],
                                    'pic'           => null,
                                    'target_date'   => null,
                                ];

                                $this->data['steps'] = $steps;
                                $this->form->fill($this->data);
                            })
                            ->modalHeading('Tambah Risiko Pekerjaan')
                            ->modalSubmitActionLabel('Tambah ke Tabel'),

                        Action::make('editWorkData')
                            ->label('Edit Data Pekerjaan')
                            ->icon('heroicon-o-pencil')
                            ->visible(fn () => false) // Hide dari header
                            ->form([
                                TextInput::make('work_sequence')
                                    ->label('Jenis Pekerjaan')
                                    ->required(),
                                Textarea::make('risk_analysis')
                                    ->label('Hazard - Risk')
                                    ->rows(3)
                                    ->required(),
                                Textarea::make('risk_control')
                                    ->label('Control Measure')
                                    ->rows(3)
                                    ->required(),
                            ])
                            ->before(function (Action $action): void {
                                $steps = $this->data['steps'] ?? [];

                                if ($this->editingIndex === null || ! isset($steps[$this->editingIndex])) {
                                    return;
                                }

                                $step = $steps[$this->editingIndex];
                                $action->getForm()->fill([
                                    'work_sequence' => $step['work_sequence'] ?? null,
                                    'risk_analysis' => $step['risk_analysis'] ?? null,
                                    'risk_control'  => $step['risk_control'] ?? null,
                                ]);
                            })
                            ->action(function (array $data): void {
                                $steps = $this->data['steps'] ?? [];

                                if ($this->editingIndex === null || ! isset($steps[$this->editingIndex])) {
                                    return;
                                }

                                $steps[$this->editingIndex]['work_sequence'] = $data['work_sequence'];
                                $steps[$this->editingIndex]['risk_analysis'] = $data['risk_analysis'];
                                $steps[$this->editingIndex]['risk_control']  = $data['risk_control'];

                                $this->data['steps'] = $steps;
                                $this->form->fill($this->data);

                                $this->editingIndex = null;

                                Notification::make()
                                    ->title('Data pekerjaan berhasil diupdate')
                                    ->success()
                                    ->send();
                            })
                            ->modalHeading('Edit Data Pekerjaan')
                            ->modalSubmitActionLabel('Simpan Perubahan'),
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
            $jsa = Jsa::create([
                'project_name'       => $this->data['project_name'] ?? null,
                'job_name'           => $this->data['job_name'] ?? null,
                'created_date'       => $this->data['created_date'] ?? null,
                'supervisor_id'      => $this->data['supervisor_id'] ?? null,
                'site_manager_id'    => $this->data['site_manager_id'] ?? null,
                'leader_hse_id'      => $this->data['leader_hse_id'] ?? null,
                'project_manager_id' => $this->data['project_manager_id'] ?? null,
            ]);

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
            ->title('JSA berhasil disimpan')
            ->success()
            ->send();
    }
}

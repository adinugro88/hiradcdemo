<?php

namespace App\Filament\Pages;

use App\Models\Jsa;
use App\Models\JsaStep;
use App\Models\User;
use App\Models\Work;
use App\Models\RiskAssessment;
use App\Models\ControlMeasure;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
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

    public function mount(): void
    {
        $this->form->fill($this->data);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->schema([
                // HEADER
                Section::make('Data Utama')
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

                // TABEL ANALISA RISIKO
                Section::make('Analisa Risiko Pekerjaan')
                    ->schema([
                        Repeater::make('steps')
                            ->label('Risiko Pekerjaan')
                            ->schema([
                                TextInput::make('work_sequence')
                                    ->label('Jenis Pekerjaan')
                                    ->disabled()
                                    ->dehydrated()
                                    ->extraAttributes(['class' => 'text-xs'])
                                    ->columnSpan(2),

                                Textarea::make('risk_analysis')
                                    ->label('Hazard - Risk')
                                    ->rows(2)
                                    ->disabled()
                                    ->dehydrated()
                                    ->extraAttributes(['class' => 'text-xs'])
                                    ->columnSpan(3),

                                Textarea::make('risk_control')
                                    ->label('Control Measure')
                                    ->rows(2)
                                    ->disabled()
                                    ->dehydrated()
                                    ->extraAttributes(['class' => 'text-xs'])
                                    ->columnSpan(3),

                                TextInput::make('pic')
                                    ->label('PIC')
                                    ->extraAttributes(['class' => 'text-xs'])
                                    ->columnSpan(2),

                                DatePicker::make('target_date')
                                    ->label('Target')
                                    ->native(false)
                                    ->extraAttributes(['class' => 'text-xs'])
                                    ->columnSpan(2),
                            ])
                            ->columns(12)
                            ->reorderable(false)
                            ->defaultItems(0)
                            ->columnSpanFull(),
                    ])
                    ->compact()
                    ->columnSpanFull(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            // Modul action: Edit & Hapus JSA (placeholder, bisa disesuaikan)
            Action::make('editJsa')
                ->label('Edit JSA')
                ->icon('heroicon-o-pencil-square')
                ->visible(fn () => ! empty($this->data['project_name'])), // contoh kondisi
            Action::make('hapusJsa')
                ->label('Hapus JSA')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function () {
                    // isi sendiri kalau page ini nanti dipakai untuk edit existing record
                }),

            // Ambil auto dari HIRADC
            Action::make('ambilTemplateHiradc')
                ->label('Ambil dari HIRADC')
                ->icon('heroicon-o-list-bullet')
                ->form([
                    CheckboxList::make('work_ids')
                        ->label('Pilih Jenis Pekerjaan (dari HIRADC)')
                        ->options(function () {
                            return Work::query()
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->toArray();
                        })
                        ->columns(1)
                        ->searchable()
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $steps = $this->data['steps'] ?? [];

                    $workIds = $data['work_ids'] ?? [];
                    if (empty($workIds)) {
                        return;
                    }

                    $works = Work::query()
                        ->whereIn('id', $workIds)
                        ->with(['hazards.riskAssessments', 'hazards.controlMeasures'])
                        ->orderBy('name')
                        ->get();

                    foreach ($works as $work) {
                        foreach ($work->hazards as $hazard) {
                            foreach ($hazard->riskAssessments as $risk) {
                                foreach ($hazard->controlMeasures as $cm) {
                                    $riskText    = trim($hazard->name.' - '.$risk->description);
                                    $controlText = $cm->basic_measure;

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

                    $this->data['steps'] = $steps;
                    $this->form->fill($this->data);
                })
                ->modalHeading('Ambil Template dari HIRADC')
                ->modalSubmitActionLabel('Masukkan ke Tabel'),

            // Tombol popup untuk tambah risiko manual, masuk ke tabel juga
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
                    $steps = $this->data['steps'] ?? [];

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
        ];
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

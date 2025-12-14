<?php

namespace App\Filament\Pages;

use App\Models\Jsa;
use App\Models\JsaStep;
use App\Models\ProjectProcess;
use App\Models\User;
use App\Models\Work;
use App\Models\WorkProcess;
use App\Models\Hazard;
use App\Models\RiskAssessment;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
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
                // ===== HEADER DALAM CARD PUTIH
                Section::make()
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

                // ===== PEKERJAAN UTAMA (OPSIONAL)
                Select::make('selected_work_id')
                    ->label('Pekerjaan Utama (opsional)')
                    ->options(
                        ProjectProcess::query()->orderBy('process')->pluck('process', 'id')
                    )
                    ->searchable()
                    ->reactive()
                    ->columnSpanFull(),

                // ===== ANALISA RISIKO (manual + dari template HIRADC)
                Section::make('Analisa Risiko Pekerjaan')
                    ->schema([
                        Repeater::make('steps')
                            ->label('Risiko Pekerjaan')
                            ->schema([
                                TextInput::make('step_number')
                                    ->label('No')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated()
                                    ->extraAttributes(['class' => 'text-xs']),

                                TextInput::make('work_sequence')
                                    ->label('Urutan Pekerjaan')
                                    ->required()
                                    ->extraAttributes(['class' => 'text-xs']),

                                Textarea::make('risk_analysis')
                                    ->label('Analisa Risiko (Hazard - Risk Description)')
                                    ->rows(2)
                                    ->required()
                                    ->extraAttributes(['class' => 'text-xs']),

                                Textarea::make('risk_control')
                                    ->label('Pengendalian Risiko')
                                    ->rows(2)
                                    ->required()
                                    ->extraAttributes(['class' => 'text-xs']),

                                TextInput::make('pic')
                                    ->label('PIC')
                                    ->extraAttributes(['class' => 'text-xs']),

                                DatePicker::make('target_date')
                                    ->label('Target')
                                    ->native(false)
                                    ->extraAttributes(['class' => 'text-xs']),
                            ])
                            ->columns(6)
                            ->reorderable(false)
                            ->defaultItems(0)
                            ->columnSpanFull()
                            ->addActionLabel('Tambah Baris'),
                    ])
                    ->compact()
                    ->columnSpanFull(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('ambilTemplateHiradc')
                ->label('Ambil Template HIRADC')
                ->icon('heroicon-o-list-bullet')
                ->form([
                    Select::make('project_process_id')
                        ->label('Pekerjaan Utama')
                        ->options(
                            ProjectProcess::query()->orderBy('process')->pluck('process', 'id')
                        )
                        ->required()
                        ->reactive(),

                    // pilih turunan pekerjaan dulu
                    CheckboxList::make('work_children_ids')
                        ->label('Pilih Template Pekerjaan (HIRADC)')
                        ->options(function ($get) {
                            $projectProcessId = $get('project_process_id');

                            if (! $projectProcessId) {
                                return [];
                            }

                            $workIds = WorkProcess::query()
                                ->where('project_process_id', $projectProcessId)
                                ->pluck('work_id');

                            return Work::query()
                                ->whereIn('id', $workIds)
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->toArray();
                        })
                        ->columns(1)
                        ->required(),

                    // setelah pilih pekerjaan, tampilkan daftar hazard + risk per pekerjaan
                    Repeater::make('selected_hazards')
                        ->label('Daftar Hazard & Risk (cek yang akan diambil)')
                        ->schema([
                            Checkbox::make('selected')
                                ->label('Ambil')
                                ->default(true),

                            TextInput::make('work_name')
                                ->label('Pekerjaan')
                                ->disabled(),

                            TextInput::make('hazard_name')
                                ->label('Hazard')
                                ->disabled(),

                            Textarea::make('risk_description')
                                ->label('Risk Description')
                                ->rows(2)
                                ->disabled(),
                        ])
                        ->columns(4)
                        ->defaultItems(0)
                        ->columnSpanFull()
                        ->afterStateHydrated(function (Repeater $component, $state, callable $set, callable $get) {
                            // Kalau sudah pernah diisi, jangan overwrite
                            if (! empty($state)) {
                                return;
                            }

                            $workIds = $get('work_children_ids') ?? [];
                            if (empty($workIds)) {
                                return;
                            }

                            // Ambil hazard + risk untuk semua work terpilih
                            $works = Work::query()
                                ->whereIn('id', $workIds)
                                ->with(['hazards.riskAssessments'])
                                ->orderBy('name')
                                ->get();

                            $items = [];

                            foreach ($works as $work) {
                                foreach ($work->hazards as $hazard) {
                                    foreach ($hazard->riskAssessments as $ra) {
                                        $items[] = [
                                            'selected'         => true,
                                            'work_id'          => $work->id,
                                            'work_name'        => $work->name,
                                            'hazard_id'        => $hazard->id,
                                            'hazard_name'      => $hazard->name,
                                            'risk_id'          => $ra->id,
                                            'risk_description' => $ra->description,
                                        ];
                                    }
                                }
                            }

                            $set('selected_hazards', $items);
                        }),
                ])
                ->action(function (array $data): void {
                    $steps = $this->data['steps'] ?? [];
                    $startIndex = count($steps);

                    foreach ($data['selected_hazards'] ?? [] as $item) {
                        if (empty($item['selected'])) {
                            continue; // hanya ambil yang dicentang
                        }

                        $workName   = $item['work_name']        ?? '';
                        $hazardName = $item['hazard_name']      ?? '';
                        $riskDesc   = $item['risk_description'] ?? '';

                        $riskText = trim($hazardName . ' - ' . $riskDesc);

                        $steps[] = [
                            'step_number'   => null,          // diisi saat submit
                            'work_sequence' => $workName,
                            'risk_analysis' => $riskText,
                            'risk_control'  => null,
                            'pic'           => null,
                            'target_date'   => null,
                        ];
                    }

                    $this->data['steps'] = $steps;
                    $this->form->fill($this->data);
                })
                ->modalHeading('Ambil Template HIRADC')
                ->modalSubmitActionLabel('Masukkan ke Tabel'),
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

            foreach (($this->data['steps'] ?? []) as $index => $step) {
                JsaStep::create([
                    'jsa_id'        => $jsa->id,
                    'step_number'   => $index + 1,
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

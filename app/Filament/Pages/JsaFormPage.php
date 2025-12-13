<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Repeater;

use Filament\Schemas\Components\Grid;



class JsaFormPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.jsa-form-page';

    protected static ?string $title = 'JSA';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentText;

    public array $data = [];

   /** ================= FORM SCHEMA ================= */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->schema([

                /** ===== HEADER JSA ===== */
                Section::make('Informasi JSA')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('project_name')
                                    ->label('Nama Proyek')
                                    ->required(),

                                Forms\Components\DatePicker::make('created_date')
                                    ->label('Tanggal Pembuatan')
                                    ->required(),

                                Forms\Components\TextInput::make('job_name')
                                    ->label('Nama Pekerjaan')
                                    ->required(),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('supervisor_id')
                                    ->label('Dibuat Oleh (Supervisor)')
                                    ->relationship('supervisor', 'name')
                                    ->searchable()
                                    ->required(),

                                Forms\Components\Select::make('site_manager_id')
                                    ->label('Diperiksa Oleh (Site Manager)')
                                    ->relationship('siteManager', 'name')
                                    ->searchable()
                                    ->required(),

                                Forms\Components\Select::make('leader_hse_id')
                                    ->label('Leader HSE Proyek')
                                    ->relationship('leaderHse', 'name')
                                    ->searchable()
                                    ->required(),

                                Forms\Components\Select::make('project_manager_id')
                                    ->label('Disetujui Oleh (Project Manager)')
                                    ->relationship('projectManager', 'name')
                                    ->searchable()
                                    ->required(),
                            ]),
                    ]),

                /** ===== TABEL ANALISA RISIKO ===== */
                Section::make('Analisa Risiko Pekerjaan')
                    ->schema([
                        Repeater::make('steps')
                            ->label('')
                            ->schema([
                               Forms\Components\TextInput::make('step_number')
                                    ->label('No')
                                    ->numeric()
                                    ->disabled() // tidak bisa diedit
                                    ->dehydrated() // tetap tersimpan
                                    ->default(1)
                                    ->afterStateHydrated(function (Forms\Components\TextInput $component) {
                                        // isi hanya jika kosong
                                        if (! $component->getState()) {
                                            $component->state(
                                                count($component->getContainer()->getParentComponent()->getState() ?? [])
                                            );
                                        }
                                    })
                                    ->extraAttributes([
                                        'class' => 'w-20 text-center', // 👈 kecil & rapi
                                    ]),

                                Forms\Components\Textarea::make('work_sequence')
                                    ->label('Urutan Pekerjaan')
                                    ->required(),

                                Forms\Components\Textarea::make('risk_analysis')
                                    ->label('Analisa Risiko')
                                    ->required(),

                                Forms\Components\Textarea::make('risk_control')
                                    ->label('Pengendalian Risiko')
                                    ->required(),

                                Forms\Components\TextInput::make('pic')
                                    ->label('PIC'),

                                Forms\Components\DatePicker::make('target_date')
                                    ->label('Target Waktu'),
                            ])
                            ->columns(6)
                            ->defaultItems(1)
                            ->addActionLabel('Tambah Langkah Pekerjaan')
                            ->reorderable(false)
                            ->required(),
                    ]),
            ]);
    }

    /** ================= SUBMIT ================= */
    public function submit(): void
    {
        DB::transaction(function () {

            $jsa = Jsa::create([
                'project_name' => $this->data['project_name'],
                'job_name' => $this->data['job_name'],
                'created_date' => $this->data['created_date'],
                'supervisor_id' => $this->data['supervisor_id'],
                'site_manager_id' => $this->data['site_manager_id'],
                'leader_hse_id' => $this->data['leader_hse_id'],
                'project_manager_id' => $this->data['project_manager_id'],
            ]);

            foreach ($this->data['steps'] as $step) {
                JsaStep::create([
                    'jsa_id' => $jsa->id,
                    'step_number' => $step['step_number'],
                    'work_sequence' => $step['work_sequence'],
                    'risk_analysis' => $step['risk_analysis'],
                    'risk_control' => $step['risk_control'],
                    'pic' => $step['pic'] ?? null,
                    'target_date' => $step['target_date'] ?? null,
                ]);
            }
        });

        Notification::make()
            ->title('JSA berhasil disimpan')
            ->success()
            ->send();

        $this->redirect(static::getUrl());
    }

}

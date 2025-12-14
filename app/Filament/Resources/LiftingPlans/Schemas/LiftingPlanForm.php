<?php

namespace App\Filament\Resources\LiftingPlans\Schemas;

use App\Models\Jsa;
use App\Models\LiftingEquipment;
use App\Models\LiftingGear;
use App\Models\Project;
use App\Models\ProjectProcess;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LiftingPlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Dokumen')
                    ->schema([
                        TextInput::make('document_number')
                            ->label('Nomor Dokumen')
                            ->required(),
                        TextInput::make('revision')
                            ->label('Revisi')
                            ->default('0')
                            ->maxLength(50),
                        TextInput::make('form_code')
                            ->label('Kode Form')
                            ->default('FM/HSE-1/20')
                            ->maxLength(50),
                        Select::make('status')
                            ->label('Status')
                            ->required()
                            ->options([
                                'draft' => 'Draft',
                                'submitted' => 'Submitted',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                'closed' => 'Closed',
                            ]),
                    ])->columns(2),

                Section::make('Proyek')
                    ->schema([
                        Select::make('project_id')
                            ->label('Project')
                            ->relationship('project', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('project_process_id')
                            ->label('Project Process')
                            ->options(fn() => ProjectProcess::orderBy('process')->pluck('process', 'id')->toArray())
                            ->searchable()
                            ->preload(),
                        Select::make('jsa_id')
                            ->label('JSA')
                            ->options(fn() => Jsa::orderBy('project_name')->pluck('project_name', 'id')->toArray())
                            ->searchable()
                            ->preload(),
                        DatePicker::make('plan_date')
                            ->label('Tanggal Rencana')
                            ->required(),
                        TextInput::make('location')
                            ->label('Lokasi')
                            ->maxLength(255),
                        Select::make('created_by')
                            ->label('Dibuat Oleh')
                            ->options(fn() => User::orderBy('name')->pluck('name', 'id')->toArray())
                            ->searchable()
                            ->preload(),
                    ])->columns(2),

                Section::make('Rincian Angkatan')
                    ->schema([
                        TextInput::make('material_type')
                            ->label('Jenis Material')
                            ->maxLength(255),
                        TextInput::make('maximum_load_ton')
                            ->label('Beban Maks (ton)')
                            ->numeric()
                            ->step('0.01'),
                        TextInput::make('crane_type')
                            ->label('Jenis Crane')
                            ->maxLength(255),
                        Select::make('lifting_type')
                            ->label('Kategori Lifting')
                            ->required()
                            ->options([
                                'critical' => 'Critical',
                                'complex' => 'Complex',
                                'routine' => 'Routine',
                            ]),
                        Textarea::make('communication_method')
                            ->label('Metode Komunikasi')
                            ->rows(2),
                    ])->columns(2),

                Section::make('Alat Angkat Yang Digunakan')
                    ->schema([
                        Repeater::make('equipments')
                            ->relationship()
                            ->schema([
                                Select::make('equipment_id')
                                    ->label('Equipment')
                                    ->options(fn() => LiftingEquipment::orderBy('equipment_name')->pluck('equipment_name', 'id')->toArray())
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                Select::make('role')
                                    ->label('Peran')
                                    ->options([
                                        'main' => 'Main',
                                        'tailing' => 'Tailing',
                                        'support' => 'Support',
                                        'other' => 'Other',
                                    ])
                                    ->default('main')
                                    ->required(),
                                TextInput::make('notes')
                                    ->label('Catatan')
                                    ->maxLength(255),
                            ])
                            ->columns(2)
                            ->reorderable()
                            ->grid(1),
                    ]),

                Section::make('Perlengkapan Angkat (Gear)')
                    ->schema([
                        Repeater::make('gears')
                            ->relationship()
                            ->schema([
                                Select::make('gear_id')
                                    ->label('Gear')
                                    ->options(fn() => LiftingGear::orderBy('gear_code')->pluck('gear_code', 'id')->toArray())
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                TextInput::make('used_quantity')
                                    ->label('Jumlah')
                                    ->numeric()
                                    ->minValue(1)
                                    ->default(1),
                                TextInput::make('size_used')
                                    ->label('Ukuran Digunakan')
                                    ->maxLength(255),
                                TextInput::make('swl_used_ton')
                                    ->label('SWL Digunakan (ton)')
                                    ->numeric()
                                    ->step('0.01'),
                            ])
                            ->columns(2)
                            ->reorderable()
                            ->grid(1),
                    ]),

                Section::make('Load Breakdown')
                    ->schema([
                        Group::make()
                            ->relationship('planLoad')
                            ->schema([
                                TextInput::make('weight_material_ton')
                                    ->label('Material (ton)')
                                    ->numeric()
                                    ->step('0.01'),
                                TextInput::make('weight_shackle_ton')
                                    ->label('Shackle (ton)')
                                    ->numeric()
                                    ->step('0.01'),
                                TextInput::make('weight_hook_ton')
                                    ->label('Hook (ton)')
                                    ->numeric()
                                    ->step('0.01'),
                                TextInput::make('weight_sling_ton')
                                    ->label('Sling (ton)')
                                    ->numeric()
                                    ->step('0.01'),
                                TextInput::make('total_weight_ton')
                                    ->label('Total (ton)')
                                    ->numeric()
                                    ->step('0.01'),
                            ])->columns(2),
                    ]),

                Section::make('Data Teknis')
                    ->schema([
                        Repeater::make('technicalData')
                            ->relationship()
                            ->schema([
                                Select::make('equipment_id')
                                    ->label('Equipment')
                                    ->options(fn() => LiftingEquipment::orderBy('equipment_name')->pluck('equipment_name', 'id')->toArray())
                                    ->searchable()
                                    ->preload(),
                                TextInput::make('max_equipment_capacity_ton')
                                    ->label('Kapasitas Maks Alat (ton)')
                                    ->numeric()
                                    ->step('0.01'),
                                TextInput::make('main_boom_length_m')
                                    ->label('Boom Length (m)')
                                    ->numeric()
                                    ->step('0.01'),
                                TextInput::make('working_radius_m')
                                    ->label('Radius Kerja (m)')
                                    ->numeric()
                                    ->step('0.01'),
                                TextInput::make('lifting_angle_deg')
                                    ->label('Angle (deg)')
                                    ->numeric()
                                    ->step('0.01'),
                                Select::make('outrigger_condition')
                                    ->label('Outrigger')
                                    ->options([
                                        'full' => 'Full',
                                        'partial' => 'Partial',
                                        'not_applicable' => 'N/A',
                                    ])
                                    ->default('not_applicable'),
                                TextInput::make('lifting_capacity_ton')
                                    ->label('Lifting Capacity (ton)')
                                    ->numeric()
                                    ->step('0.01'),
                                TextInput::make('load_chart_source')
                                    ->label('Referensi Load Chart')
                                    ->maxLength(255),
                            ])
                            ->columns(2)
                            ->reorderable()
                            ->grid(1),
                    ]),

                Section::make('Safety Factor')
                    ->schema([
                        Group::make()
                            ->relationship('safety')
                            ->schema([
                                TextInput::make('total_load_ton')
                                    ->label('Total Load (ton)')
                                    ->numeric()
                                    ->step('0.01'),
                                TextInput::make('lifting_capacity_ton')
                                    ->label('Lifting Capacity (ton)')
                                    ->numeric()
                                    ->step('0.01'),
                                TextInput::make('safety_factor')
                                    ->label('SF (capacity / load)')
                                    ->numeric()
                                    ->step('0.0001'),
                                Select::make('safety_status')
                                    ->label('Status')
                                    ->options([
                                        'safe' => 'Safe',
                                        'unsafe' => 'Unsafe',
                                        'unknown' => 'Unknown',
                                    ])
                                    ->default('unknown'),
                                TextInput::make('rule_note')
                                    ->label('Catatan Aturan')
                                    ->default('Safe when SF > 1.2')
                                    ->maxLength(255),
                            ])->columns(2),
                    ]),

                Section::make('Approval')
                    ->schema([
                        Repeater::make('approvals')
                            ->relationship()
                            ->schema([
                                Select::make('user_id')
                                    ->label('User')
                                    ->options(fn() => User::orderBy('name')->pluck('name', 'id')->toArray())
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                Select::make('role')
                                    ->label('Peran')
                                    ->required()
                                    ->options([
                                        'dibuat' => 'Dibuat',
                                        'diperiksa' => 'Diperiksa',
                                        'disetujui' => 'Disetujui',
                                        'diketahui' => 'Diketahui',
                                    ]),
                                DateTimePicker::make('signed_at')
                                    ->label('Tanggal TTD'),
                                TextInput::make('note')
                                    ->label('Catatan')
                                    ->maxLength(255),
                            ])
                            ->columns(2)
                            ->reorderable()
                            ->grid(1),
                    ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Inspections\Schemas;

use App\Models\LiftingEquipment;
use App\Models\LiftingGear;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InspectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Objek Inspeksi')
                    ->schema([
                        Select::make('inspectable_type')
                            ->label('Jenis Objek')
                            ->required()
                            ->live()
                            ->options([
                                'equipment' => 'Equipment',
                                'gear' => 'Gear',
                            ]),
                        Select::make('inspectable_id')
                            ->label('Pilih Objek')
                            ->required()
                            ->searchable()
                            ->options(function ($get) {
                                $type = $get('inspectable_type');

                                if ($type === 'equipment') {
                                    return LiftingEquipment::orderBy('equipment_name')
                                        ->pluck('equipment_name', 'id')
                                        ->toArray();
                                }

                                if ($type === 'gear') {
                                    return LiftingGear::orderBy('gear_code')
                                        ->pluck('gear_code', 'id')
                                        ->toArray();
                                }

                                return [];
                            }),
                        Select::make('inspection_type')
                            ->label('Tipe Inspeksi')
                            ->required()
                            ->options([
                                'daily' => 'Daily',
                                'monthly' => 'Monthly',
                                'quarterly' => 'Quarterly',
                                'yearly' => 'Yearly',
                                'load_test' => 'Load Test',
                                'third_party' => 'Third Party',
                                'pre_use' => 'Pre Use',
                                'post_repair' => 'Post Repair',
                                'other' => 'Other',
                            ]),
                        DatePicker::make('inspection_date')
                            ->label('Tanggal Inspeksi')
                            ->required(),
                        TextInput::make('validity_days')
                            ->label('Validity (hari)')
                            ->numeric()
                            ->step(1),
                        DatePicker::make('valid_until')
                            ->label('Berlaku Sampai'),
                        Select::make('result')
                            ->label('Hasil')
                            ->required()
                            ->options([
                                'pass' => 'Pass',
                                'fail' => 'Fail',
                                'conditional' => 'Conditional',
                            ]),
                    ])->columns(2),

                Section::make('Temuan')
                    ->schema([
                        Textarea::make('findings')
                            ->label('Findings')
                            ->rows(2),
                        Textarea::make('corrective_action')
                            ->label('Tindakan Korektif')
                            ->rows(2),
                    ]),

                Section::make('Inspector')
                    ->schema([
                        Select::make('inspector_user_id')
                            ->label('Inspector (User)')
                            ->searchable()
                            ->preload()
                            ->options(fn() => User::orderBy('name')->pluck('name', 'id')->toArray()),
                        TextInput::make('inspector_name')
                            ->label('Inspector Name (Eksternal)')
                            ->maxLength(255),
                        TextInput::make('inspector_company')
                            ->label('Company')
                            ->maxLength(255),
                        TextInput::make('certificate_number')
                            ->label('Certificate Number')
                            ->maxLength(255),
                        TextInput::make('certificate_file')
                            ->label('Path Certificate File')
                            ->maxLength(255),
                        DatePicker::make('next_due_date')
                            ->label('Next Due Date'),
                    ])->columns(2),
            ]);
    }
}

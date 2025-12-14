<?php

namespace App\Filament\Resources\LiftingEquipments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LiftingEquipmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Alat')
                    ->schema([
                        TextInput::make('equipment_code')
                            ->label('Kode Alat')
                            ->required()
                            ->maxLength(100),
                        TextInput::make('equipment_name')
                            ->label('Nama Alat')
                            ->required()
                            ->maxLength(255),
                        Select::make('equipment_type')
                            ->label('Tipe Alat')
                            ->required()
                            ->options([
                                'crane' => 'Crane',
                                'forklift' => 'Forklift',
                                'hoist' => 'Hoist',
                                'chain_block' => 'Chain Block',
                                'mobile_crane' => 'Mobile Crane',
                                'tower_crane' => 'Tower Crane',
                                'other' => 'Lainnya',
                            ]),
                        Select::make('status')
                            ->label('Status')
                            ->required()
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'maintenance' => 'Maintenance',
                                'retired' => 'Retired',
                            ]),
                    ])->columns(2),

                Section::make('Detail Teknis')
                    ->schema([
                        TextInput::make('brand')
                            ->label('Merek')
                            ->maxLength(100),
                        TextInput::make('model')
                            ->label('Model')
                            ->maxLength(100),
                        TextInput::make('serial_number')
                            ->label('Serial Number')
                            ->maxLength(150),
                        TextInput::make('year')
                            ->label('Tahun')
                            ->numeric()
                            ->minValue(1900)
                            ->maxValue((int) date('Y') + 1),
                        TextInput::make('max_capacity_ton')
                            ->label('Kapasitas Maks (ton)')
                            ->numeric()
                            ->step('0.01'),
                        TextInput::make('load_chart_ref')
                            ->label('Referensi Load Chart')
                            ->maxLength(255),
                        TextInput::make('boom_length_min_m')
                            ->label('Panjang Boom Min (m)')
                            ->numeric()
                            ->step('0.01'),
                        TextInput::make('boom_length_max_m')
                            ->label('Panjang Boom Max (m)')
                            ->numeric()
                            ->step('0.01'),
                    ])->columns(2),

                Section::make('Kepemilikan')
                    ->schema([
                        TextInput::make('owner_company')
                            ->label('Pemilik / Company')
                            ->maxLength(255),
                    ])->columns(1),
            ]);
    }
}

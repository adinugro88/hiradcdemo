<?php

namespace App\Filament\Resources\LiftingGears\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LiftingGearForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Gear')
                    ->schema([
                        TextInput::make('gear_code')
                            ->label('Kode Gear')
                            ->required()
                            ->maxLength(100),
                        Select::make('gear_type')
                            ->label('Tipe Gear')
                            ->required()
                            ->options([
                                'sling_wire' => 'Sling Wire',
                                'sling_webbing' => 'Sling Webbing',
                                'shackle' => 'Shackle',
                                'hook' => 'Hook',
                                'spreader_bar' => 'Spreader Bar',
                                'master_link' => 'Master Link',
                                'other' => 'Lainnya',
                            ]),
                        TextInput::make('description')
                            ->label('Deskripsi')
                            ->maxLength(255),
                        Select::make('status')
                            ->label('Status')
                            ->required()
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'discarded' => 'Discarded',
                                'maintenance' => 'Maintenance',
                            ]),
                    ])->columns(2),

                Section::make('Spesifikasi')
                    ->schema([
                        TextInput::make('size_spec')
                            ->label('Ukuran / Spec')
                            ->maxLength(255),
                        TextInput::make('swl_ton')
                            ->label('SWL (ton)')
                            ->numeric()
                            ->step('0.01'),
                        TextInput::make('wll_ton')
                            ->label('WLL (ton)')
                            ->numeric()
                            ->step('0.01'),
                        TextInput::make('color_code')
                            ->label('Kode Warna')
                            ->maxLength(50),
                    ])->columns(2),

                Section::make('Pabrikasi')
                    ->schema([
                        TextInput::make('manufacturer')
                            ->label('Produsen')
                            ->maxLength(255),
                        TextInput::make('serial_number')
                            ->label('Serial Number')
                            ->maxLength(150),
                    ])->columns(2),
            ]);
    }
}

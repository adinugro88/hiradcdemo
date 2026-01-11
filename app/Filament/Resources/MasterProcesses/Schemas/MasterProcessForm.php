<?php

namespace App\Filament\Resources\MasterProcesses\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class MasterProcessForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')
                    ->label('Nama Proses')
                    ->required()
                    ->placeholder('Masukkan nama proses')
                    ->columnSpanFull(),
            ]);
    }
}

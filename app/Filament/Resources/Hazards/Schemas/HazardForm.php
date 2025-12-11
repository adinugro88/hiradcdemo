<?php

namespace App\Filament\Resources\Hazards\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class HazardForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('work_process_id')
                    ->relationship('workProcess', 'id')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Work Process'),
                TextInput::make('name')
                    ->label('Hazard Name')
                    ->required()
                    ->maxLength(255),
                Textarea::make('risk_description')
                    ->label('Risk Description')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                Textarea::make('regulations')
                    ->label('Regulations')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}

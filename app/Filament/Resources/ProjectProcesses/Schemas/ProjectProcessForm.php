<?php

namespace App\Filament\Resources\ProjectProcesses\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProjectProcessForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('project_id')
                    ->relationship('project', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('process')
                    ->label('Process Name')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}

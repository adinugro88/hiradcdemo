<?php

namespace App\Filament\Resources\WorkProcesses\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class WorkProcessForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('project_process_id')
                    ->relationship('projectProcess', 'process')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('work_id')
                    ->relationship('work', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }
}

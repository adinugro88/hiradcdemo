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
                    ->relationship('projectProcess', 'id')
                    ->required(),
                Select::make('work_id')
                    ->relationship('work', 'name')
                    ->required(),
            ]);
    }
}

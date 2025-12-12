<?php

namespace App\Filament\Resources\ProjectEmployeeRoles\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class ProjectEmployeeRoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('project_id')
                    ->label('Project')
                    ->relationship('project', 'name')
                    ->searchable()
                    ->required(),
                Select::make('employee_id')
                    ->label('Employee')
                    ->relationship('employee', 'name')
                    ->searchable()
                    ->required(),
                Select::make('role')
                    ->label('Role')
                    ->options([
                        'dibuat' => 'Dibuat',
                        'diperiksa' => 'Diperiksa',
                        'disetujui' => 'Disetujui',
                        'diketahui' => 'Diketahui',
                    ])
                    ->required(),
            ]);
    }
}

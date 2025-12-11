<?php

namespace App\Filament\Resources\ProjectProcesses;

use App\Filament\Resources\ProjectProcesses\Pages\CreateProjectProcess;
use App\Filament\Resources\ProjectProcesses\Pages\EditProjectProcess;
use App\Filament\Resources\ProjectProcesses\Pages\ListProjectProcesses;
use App\Filament\Resources\ProjectProcesses\Schemas\ProjectProcessForm;
use App\Filament\Resources\ProjectProcesses\Tables\ProjectProcessesTable;
use App\Models\ProjectProcess;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProjectProcessResource extends Resource
{
    protected static ?string $model = ProjectProcess::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ProjectProcessForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectProcessesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\WorksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProjectProcesses::route('/'),
            'create' => CreateProjectProcess::route('/create'),
            'edit' => EditProjectProcess::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\WorkProcesses;

use App\Filament\Resources\WorkProcesses\Pages\CreateWorkProcess;
use App\Filament\Resources\WorkProcesses\Pages\EditWorkProcess;
use App\Filament\Resources\WorkProcesses\Pages\ListWorkProcesses;
use App\Filament\Resources\WorkProcesses\Schemas\WorkProcessForm;
use App\Filament\Resources\WorkProcesses\Tables\WorkProcessesTable;
use App\Models\WorkProcess;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WorkProcessResource extends Resource
{
    protected static ?string $model = WorkProcess::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return WorkProcessForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkProcessesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWorkProcesses::route('/'),
            'create' => CreateWorkProcess::route('/create'),
            'edit' => EditWorkProcess::route('/{record}/edit'),
        ];
    }
}

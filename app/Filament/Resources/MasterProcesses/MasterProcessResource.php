<?php

namespace App\Filament\Resources\MasterProcesses;

use App\Filament\Resources\MasterProcesses\Pages\CreateMasterProcess;
use App\Filament\Resources\MasterProcesses\Pages\EditMasterProcess;
use App\Filament\Resources\MasterProcesses\Pages\ListMasterProcesses;
use App\Filament\Resources\MasterProcesses\RelationManagers\MasterWorkProcessesRelationManager;
use App\Filament\Resources\MasterProcesses\Schemas\MasterProcessForm;
use App\Filament\Resources\MasterProcesses\Tables\MasterProcessesTable;
use App\Models\MasterProcess;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MasterProcessResource extends Resource
{
    protected static ?string $model = MasterProcess::class;

    protected static ?int $navigationSort = 2;

    protected static string|UnitEnum|null $navigationGroup = 'HIRADC';

    protected static ?string $navigationLabel = 'Master Proses';

    protected static ?string $label = 'Master Proses';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedListBullet;

    public static function form(Schema $schema): Schema
    {
        return MasterProcessForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MasterProcessesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            MasterWorkProcessesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMasterProcesses::route('/'),
            'create' => CreateMasterProcess::route('/create'),
            'edit' => EditMasterProcess::route('/{record}/edit'),
        ];
    }
}

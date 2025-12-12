<?php

namespace App\Filament\Resources\ProjectEmployeeRoles;

use App\Filament\Resources\ProjectEmployeeRoles\Pages\CreateProjectEmployeeRole;
use App\Filament\Resources\ProjectEmployeeRoles\Pages\EditProjectEmployeeRole;
use App\Filament\Resources\ProjectEmployeeRoles\Pages\ListProjectEmployeeRoles;
use App\Filament\Resources\ProjectEmployeeRoles\Schemas\ProjectEmployeeRoleForm;
use App\Filament\Resources\ProjectEmployeeRoles\Tables\ProjectEmployeeRolesTable;
use App\Models\ProjectEmployeeRole;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProjectEmployeeRoleResource extends Resource
{
    protected static ?string $model = ProjectEmployeeRole::class;
    
    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return ProjectEmployeeRoleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectEmployeeRolesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProjectEmployeeRoles::route('/'),
            'create' => CreateProjectEmployeeRole::route('/create'),
            'edit' => EditProjectEmployeeRole::route('/{record}/edit'),
        ];
    }
}

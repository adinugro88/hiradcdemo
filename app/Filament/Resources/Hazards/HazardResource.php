<?php

namespace App\Filament\Resources\Hazards;

use App\Filament\Resources\Hazards\Pages\CreateHazard;
use App\Filament\Resources\Hazards\Pages\EditHazard;
use App\Filament\Resources\Hazards\Pages\ListHazards;
use App\Filament\Resources\Hazards\Schemas\HazardForm;
use App\Filament\Resources\Hazards\Tables\HazardsTable;
use App\Models\Hazard;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class HazardResource extends Resource
{
    protected static ?string $model = Hazard::class;

    // public static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ExclamationCircle;

    public static function form(Schema $schema): Schema
    {
        return HazardForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HazardsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ControlMeasuresRelationManager::class,
            RelationManagers\RiskAssessmentsRelationManager::class,
            RelationManagers\RegulationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHazards::route('/'),
            'create' => CreateHazard::route('/create'),
            'edit' => EditHazard::route('/{record}/edit'),
        ];
    }
}

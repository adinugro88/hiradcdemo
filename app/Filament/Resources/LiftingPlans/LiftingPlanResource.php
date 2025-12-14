<?php

namespace App\Filament\Resources\LiftingPlans;

use App\Filament\Resources\LiftingPlans\Pages\CreateLiftingPlan;
use App\Filament\Resources\LiftingPlans\Pages\EditLiftingPlan;
use App\Filament\Resources\LiftingPlans\Pages\ListLiftingPlans;
use App\Filament\Resources\LiftingPlans\Schemas\LiftingPlanForm;
use App\Filament\Resources\LiftingPlans\Tables\LiftingPlanTable;
use App\Models\LiftingPlan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LiftingPlanResource extends Resource
{
    protected static ?string $model = LiftingPlan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ClipboardDocumentList;

    // protected static ?string $navigationGroup = 'Lifting Management';

    protected static ?int $navigationSort = 8;

    public static function form(Schema $schema): Schema
    {
        return LiftingPlanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LiftingPlanTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLiftingPlans::route('/'),
            'create' => CreateLiftingPlan::route('/create'),
            'edit' => EditLiftingPlan::route('/{record}/edit'),
        ];
    }
}

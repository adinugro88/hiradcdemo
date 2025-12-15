<?php

namespace App\Filament\Resources\LiftingGears;

use App\Filament\Resources\LiftingGears\Pages\CreateLiftingGear;
use App\Filament\Resources\LiftingGears\Pages\EditLiftingGear;
use App\Filament\Resources\LiftingGears\Pages\ListLiftingGears;
use App\Filament\Resources\LiftingGears\Schemas\LiftingGearForm;
use App\Filament\Resources\LiftingGears\Tables\LiftingGearTable;
use App\Models\LiftingGear;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LiftingGearResource extends Resource
{
    protected static ?string $model = LiftingGear::class;
    protected static string|UnitEnum|null $navigationGroup = 'Equipment';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::WrenchScrewdriver;

    // protected static ?string $navigationGroup = 'Lifting Management';

    protected static ?int $navigationSort = 11;

    public static function form(Schema $schema): Schema
    {
        return LiftingGearForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LiftingGearTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLiftingGears::route('/'),
            'create' => CreateLiftingGear::route('/create'),
            'edit' => EditLiftingGear::route('/{record}/edit'),
        ];
    }
}

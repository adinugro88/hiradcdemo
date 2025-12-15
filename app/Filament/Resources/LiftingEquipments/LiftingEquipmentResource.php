<?php

namespace App\Filament\Resources\LiftingEquipments;

use App\Filament\Resources\LiftingEquipments\Pages\CreateLiftingEquipment;
use App\Filament\Resources\LiftingEquipments\Pages\EditLiftingEquipment;
use App\Filament\Resources\LiftingEquipments\Pages\ListLiftingEquipments;
use App\Filament\Resources\LiftingEquipments\Schemas\LiftingEquipmentForm;
use App\Filament\Resources\LiftingEquipments\Tables\LiftingEquipmentTable;
use App\Models\LiftingEquipment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class LiftingEquipmentResource extends Resource
{
    protected static ?string $model = LiftingEquipment::class;
    protected static string|UnitEnum|null $navigationGroup = 'Equipment';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Truck;

    // protected static ?string $navigationGroup = 'Lifting Management';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return LiftingEquipmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LiftingEquipmentTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLiftingEquipments::route('/'),
            'create' => CreateLiftingEquipment::route('/create'),
            'edit' => EditLiftingEquipment::route('/{record}/edit'),
        ];
    }
}

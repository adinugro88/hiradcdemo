<?php

namespace App\Filament\Resources\ControlMeasures;

use App\Filament\Resources\ControlMeasures\Pages\CreateControlMeasure;
use App\Filament\Resources\ControlMeasures\Pages\EditControlMeasure;
use App\Filament\Resources\ControlMeasures\Pages\ListControlMeasures;
use App\Filament\Resources\ControlMeasures\Schemas\ControlMeasureForm;
use App\Filament\Resources\ControlMeasures\Tables\ControlMeasuresTable;
use App\Models\ControlMeasure;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ControlMeasureResource extends Resource
{
    protected static ?string $model = ControlMeasure::class;

    public static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ControlMeasureForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ControlMeasuresTable::configure($table);
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
            'index' => ListControlMeasures::route('/'),
            'create' => CreateControlMeasure::route('/create'),
            'edit' => EditControlMeasure::route('/{record}/edit'),
        ];
    }
}

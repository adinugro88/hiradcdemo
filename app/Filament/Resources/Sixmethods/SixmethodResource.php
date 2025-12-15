<?php

namespace App\Filament\Resources\Sixmethods;

use App\Filament\Resources\Sixmethods\Pages\CreateSixmethod;
use App\Filament\Resources\Sixmethods\Pages\EditSixmethod;
use App\Filament\Resources\Sixmethods\Pages\ListSixmethods;
use App\Filament\Resources\Sixmethods\RelationManagers\DetailsRelationManager;
use App\Filament\Resources\Sixmethods\Schemas\SixmethodForm;
use App\Filament\Resources\Sixmethods\Tables\SixmethodsTable;
use App\Models\Sixmethod;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SixmethodResource extends Resource
{
    protected static ?string $model = Sixmethod::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Master';

    public static function form(Schema $schema): Schema
    {
        return SixmethodForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SixmethodsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            DetailsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSixmethods::route('/'),
            'create' => CreateSixmethod::route('/create'),
            'edit' => EditSixmethod::route('/{record}/edit'),
        ];
    }
}

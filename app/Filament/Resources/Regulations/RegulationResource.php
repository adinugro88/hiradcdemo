<?php

namespace App\Filament\Resources\Regulations;

use App\Filament\Resources\Regulations\Pages\CreateRegulation;
use App\Filament\Resources\Regulations\Pages\EditRegulation;
use App\Filament\Resources\Regulations\Pages\ListRegulations;
use App\Filament\Resources\Regulations\Schemas\RegulationForm;
use App\Filament\Resources\Regulations\Tables\RegulationsTable;
use App\Models\Regulation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RegulationResource extends Resource
{
    protected static ?string $model = Regulation::class;

    public static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return RegulationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RegulationsTable::configure($table);
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
            'index' => ListRegulations::route('/'),
            'create' => CreateRegulation::route('/create'),
            'edit' => EditRegulation::route('/{record}/edit'),
        ];
    }
}

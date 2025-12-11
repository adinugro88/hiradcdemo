<?php

namespace App\Filament\Resources\RiskAssessments;

use App\Filament\Resources\RiskAssessments\Pages\CreateRiskAssessment;
use App\Filament\Resources\RiskAssessments\Pages\EditRiskAssessment;
use App\Filament\Resources\RiskAssessments\Pages\ListRiskAssessments;
use App\Filament\Resources\RiskAssessments\Schemas\RiskAssessmentForm;
use App\Filament\Resources\RiskAssessments\Tables\RiskAssessmentsTable;
use App\Models\RiskAssessment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RiskAssessmentResource extends Resource
{
    protected static ?string $model = RiskAssessment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return RiskAssessmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RiskAssessmentsTable::configure($table);
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
            'index' => ListRiskAssessments::route('/'),
            'create' => CreateRiskAssessment::route('/create'),
            'edit' => EditRiskAssessment::route('/{record}/edit'),
        ];
    }
}

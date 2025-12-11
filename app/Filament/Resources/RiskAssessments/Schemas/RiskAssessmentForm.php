<?php

namespace App\Filament\Resources\RiskAssessments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RiskAssessmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('hazard_id')
                    ->relationship('hazard', 'name')
                    ->required(),
                TextInput::make('probability_before')
                    ->required()
                    ->numeric(),
                TextInput::make('severity_before')
                    ->required()
                    ->numeric(),
                TextInput::make('total_before')
                    ->required()
                    ->numeric(),
                TextInput::make('category_before')
                    ->required(),
                TextInput::make('probability_after')
                    ->required()
                    ->numeric(),
                TextInput::make('severity_after')
                    ->required()
                    ->numeric(),
                TextInput::make('total_after')
                    ->required()
                    ->numeric(),
                TextInput::make('category_after')
                    ->required(),
            ]);
    }
}

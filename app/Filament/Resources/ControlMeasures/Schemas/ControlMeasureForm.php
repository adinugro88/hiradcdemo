<?php

namespace App\Filament\Resources\ControlMeasures\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ControlMeasureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('hazard_id')
                    ->relationship('hazard', 'name')
                    ->required(),
                Textarea::make('basic_measure')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('opportunity_measure')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('advanced_measure')
                    ->columnSpanFull(),
                Textarea::make('control_hierarchy')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}

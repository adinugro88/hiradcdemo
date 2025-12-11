<?php

namespace App\Filament\Resources\Hazards\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class HazardForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('work_id')
                    ->relationship('work', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Textarea::make('risk_description')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('regulations')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}

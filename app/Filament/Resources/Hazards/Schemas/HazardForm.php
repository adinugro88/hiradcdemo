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
                //output name of work process
                Select::make('work_id')
                    ->relationship('work', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Work Process')
                    //output name of work process
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('work_name', $state);
                    })
                    ,
                TextInput::make('name')
                    ->label('Hazard Name')
                    ->required()
                    ->maxLength(255),
                
                // Textarea::make('risk_description')
                //     ->label('Risk Description')
                //     ->required()
                //     ->rows(3)
                //     ->columnSpanFull(),
                // Textarea::make('regulations')
                //     ->label('Regulations')
                //     ->required()
                //     ->rows(3)
                //     ->columnSpanFull(),
            ]);
    }
}

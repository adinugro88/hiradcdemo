<?php

namespace App\Filament\Resources\Works\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WorkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('number')
                    ->label('No.')
                    ->required()
                    ->numeric()
                    ->minValue(1),
                TextInput::make('name')
                    ->label('Work Name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }
}

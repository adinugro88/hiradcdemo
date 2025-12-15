<?php

namespace App\Filament\Resources\Works\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WorkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Work Name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                TextInput::make('description')
                    ->label('Work Description')
                    ->nullable()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Select::make('sixmethods_id')
                    ->label('Six Methods')
                    ->relationship('sixmethod', 'name')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }
}

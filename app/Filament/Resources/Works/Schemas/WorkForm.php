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
                    ->required()
                    ->numeric(),
                TextInput::make('name')
                    ->required(),
            ]);
    }
}

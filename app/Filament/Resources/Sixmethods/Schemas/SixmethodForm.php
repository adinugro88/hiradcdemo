<?php

namespace App\Filament\Resources\Sixmethods\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SixmethodForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('description')
                    ->columnSpan('full'),
            ]);
    }
}

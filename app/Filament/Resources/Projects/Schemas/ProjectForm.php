<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('department')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('document_number')
                    ->required(),
                TextInput::make('form_code')
                    ->required(),
                TextInput::make('revision')
                    ->required(),
                TextInput::make('page_info'),
            ]);
    }
}

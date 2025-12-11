<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Project Information')
                    ->schema([
                        TextInput::make('department')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        TextInput::make('name')
                            ->label('Project Name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Document Details')
                    ->schema([
                        TextInput::make('document_number')
                            ->label('Document Number')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('form_code')
                            ->label('Form Code')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('revision')
                            ->required()
                            ->maxLength(255)
                            ->default('Rev.01'),
                        TextInput::make('page_info')
                            ->label('Page Info')
                            ->maxLength(255)
                            ->placeholder('e.g., 1 dari 1'),
                    ])
                    ->columns(2),
            ]);
    }
}

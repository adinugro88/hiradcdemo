<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),

                Select::make('position_id')
                    ->label('Position')
                    ->relationship(
                        name: 'position',      // method relasi di model User
                        titleAttribute: 'name' // kolom yang ditampilkan di tabel positions
                    )
                    ->searchable()
                    ->preload()
                    ->required(),            // ubah ke nullable() kalau tidak wajib

                Select::make('department_id')
                    ->label('Department')
                    ->relationship(
                        name: 'department',   // method relasi di model User
                        titleAttribute: 'name'
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),

                TextInput::make('password')
                    ->password()
                    ->required(),
            ]);
    }
}

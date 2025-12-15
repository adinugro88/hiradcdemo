<?php

namespace App\Filament\Resources\Works\Schemas;

use App\Models\SixmethodDetail;
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
                    ->label('Danger')
                    ->relationship('sixmethod', 'name')
                    ->nullable()
                    ->live()
                    ->columnSpanFull(),

                Select::make('six_detail_id')
                    ->label('Danger Detail')
                    ->options(
                        fn($get) =>
                        $get('sixmethods_id')
                            ? SixmethodDetail::where('sixmethod_id', $get('sixmethods_id'))
                            ->pluck('step', 'id')
                            : []
                    )
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }
}

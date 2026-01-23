<?php

namespace App\Filament\Resources\MasterProcesses\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use App\Models\Work;
use App\Models\SixmethodDetail;

class MasterProcessForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')
                    ->label('Nama Proses')
                    ->required()
                    ->placeholder('Masukkan nama proses')
                    ->columnSpanFull(),
                
                Select::make('works')
                    ->label('Pekerjaan (Works)')
                    ->relationship('works', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
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
                        Select::make('sixmethod_details_id')
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
                    ])
                    ->createOptionUsing(function (array $data) {
                        $work = Work::create($data);
                        return $work->id;
                    })
                    ->columnSpanFull(),
            ]);
    }
}


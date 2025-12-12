<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JsaResource\Pages;
use App\Filament\Resources\JsaResource\RelationManagers;
use App\Models\Jsa;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

class JsaResource extends Resource
{
    protected static ?string $model = Jsa::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::RectangleStack;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('job_name')->required(),
                DatePicker::make('created_date')->required(),
                Repeater::make('steps')
                    ->relationship('steps')
                    ->schema([
                        Textarea::make('work_sequence')->columnSpanFull(),
                        Textarea::make('risk_analysis')->rows(3)->columnSpanFull(),
                        Textarea::make('risk_control')->rows(3)->columnSpanFull(),
                        TextInput::make('pic'),
                        DatePicker::make('target_date'),
                    ])
                    ->columnSpanFull()
                    ->collapsible()
                    ->collapsed()
                    ->addActionLabel('Tambah Langkah'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('project.name')->label('Proyek'),
                TextColumn::make('job_name')->label('Pekerjaan'),
                TextColumn::make('created_date')->date(),
                TextColumn::make('steps_count')
                    ->getStateUsing(fn ($record) => $record->steps()->count())
                    ->label('Jumlah Langkah'),
            ])
            ->filters([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJsas::route('/'),
            'create' => Pages\CreateJsa::route('/create'),
            'edit' => Pages\EditJsa::route('/{record}/edit'),
        ];
    }
}
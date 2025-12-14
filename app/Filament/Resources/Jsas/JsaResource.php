<?php

namespace App\Filament\Resources\Jsas;

use App\Filament\Resources\Jsas\Pages;
use App\Models\Jsa;
use App\Models\Work;
use App\Models\WorkProcess;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

// Form components
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;
use App\Filament\Resources\Jsas\Schemas\JsaForm;

class JsaResource extends Resource
{
    protected static ?string $model = Jsa::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentText;

    public static function form(Schema $schema): Schema
    {
        return JsaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('project_name')->label('Nama Proyek'),
                TextColumn::make('work.name')->label('Nama Pekerjaan'),
                TextColumn::make('created_date')->label('Tanggal Pembuatan')->date(),
                TextColumn::make('steps_count')
                    ->label('Jumlah Langkah')
                    ->getStateUsing(fn($record) => $record->steps()->count()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListJsas::route('/'),
            'create' => Pages\CreateJsa::route('/create'),
            'edit'   => Pages\EditJsa::route('/{record}/edit'),
        ];
    }
}

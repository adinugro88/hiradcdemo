<?php

namespace App\Filament\Pages;

use App\Models\Jsa;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Support\Icons\Heroicon;
use App\Filament\Pages\JsaFormPage;
use BackedEnum;
use Filament\Actions\Action;


class JsaIndexPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $title = 'JSA';
    protected static ?string $navigationLabel = 'JSA';
    protected static ?string $slug = 'jsa'; // /admin/jsa
    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentText;


    protected function getTableQuery()
    {
        return Jsa::query();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('project_name')->label('Nama Proyek'),
            Tables\Columns\TextColumn::make('job_name')->label('Nama Pekerjaan'),
            Tables\Columns\TextColumn::make('created_date')->label('Tanggal')->date(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('edit')
                ->label('Edit')
                ->url(fn ($record) => route('filament.admin.pages.jsa-form-page', ['record' => $record->id])),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create')
                ->label('Buat JSA')
                ->url(JsaFormPage::getUrl()),
        ];
    }
}

<?php

namespace App\Filament\Pages;

use App\Models\Jsa;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use App\Filament\Pages\JsaFormPage;
use Filament\Actions\DeleteAction;

class JsaIndexPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $title = 'JSA';
    protected string $view = 'filament.pages.jsa-index-page';

    protected static ?string $navigationLabel = 'JSA';
    protected static ?string $slug = 'jsa';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentText;

    protected function getTableQuery()
    {
        return Jsa::query();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('project_name')
                ->label('Nama Proyek'),
            Tables\Columns\TextColumn::make('job_name')
                ->label('Nama Pekerjaan'),
            Tables\Columns\TextColumn::make('created_date')
                ->label('Tanggal')
                ->date(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('export_pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->url(fn(Jsa $record) => route('jsa.pdf', $record->id))
                ->openUrlInNewTab(),
            Action::make('edit')
                ->label('Edit')
                ->icon('heroicon-o-pencil')
                ->url(fn(Jsa $record) => JsaFormPage::getUrl(['record' => $record->id])),
            DeleteAction::make()
                ->label('Hapus'),
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

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

    // Tanpa action baris dulu (tidak ada edit/hapus)
    protected function getTableActions(): array
    {
        return [];
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

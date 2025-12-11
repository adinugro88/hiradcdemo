<?php

namespace App\Filament\Resources\ProjectProcesses\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WorksRelationManager extends RelationManager
{
    // pastikan relasi di model ProjectProcess: belongsToMany(Work::class, 'work_processes')
    protected static string $relationship = 'works';

    public function form(Schema $schema): Schema
    {
        // ini untuk "Create" (buat Work baru lalu otomatis ter-attach ke project_process)
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Buat Work baru dan langsung simpan ke pivot work_processes
                CreateAction::make(),
                // Ambil Work yang sudah ada lalu attach ke project_process (simpan ke pivot)
                AttachAction::make()
                    ->preloadRecordSelect(), // opsional: preload daftar Work
            ])
            ->recordActions([
                ViewAction::make()
                    ->url(fn ($record) => route('filament.admin.resources.works.edit', ['record' => $record])),
                // EditAction::make(), // jika ingin edit Work dari sini
                DetachAction::make(), // lepaskan dari pivot tanpa menghapus Work
                DeleteAction::make(), // hapus Work (hati-hati jika dipakai global)
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

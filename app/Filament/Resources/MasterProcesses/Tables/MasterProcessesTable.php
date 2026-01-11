<?php

namespace App\Filament\Resources\MasterProcesses\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class MasterProcessesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('name')
                    ->label('Nama Proses')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d-m-Y H:i')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d-m-Y H:i')
                    ->sortable(),
            ])
            ->filters([
                // Add filters here if needed
            ])
            ->actions([
                // Actions will be added by the Resource
            ])
            ->bulkActions([
                // Bulk actions will be added by the Resource
            ])
            ->paginated([10, 25, 50, 100]);
    }
}

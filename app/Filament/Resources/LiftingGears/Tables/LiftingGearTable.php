<?php

namespace App\Filament\Resources\LiftingGears\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LiftingGearTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('gear_code')
                    ->label('Kode')
                    ->searchable(),
                TextColumn::make('gear_type')
                    ->label('Tipe')
                    ->badge(),
                TextColumn::make('description')
                    ->label('Deskripsi')
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'inactive',
                        'warning' => 'maintenance',
                        'gray' => 'discarded',
                    ]),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // add filters if needed
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

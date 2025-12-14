<?php

namespace App\Filament\Resources\Inspections\Tables;

use App\Models\LiftingEquipment;
use App\Models\LiftingGear;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InspectionTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('inspectable_type')
                    ->label('Jenis')
                    ->badge(),
                TextColumn::make('inspectable_id')
                    ->label('Objek')
                    ->getStateUsing(function ($record) {
                        if ($record->inspectable_type === 'equipment') {
                            return optional(LiftingEquipment::find($record->inspectable_id))?->equipment_name ?? '-';
                        }

                        if ($record->inspectable_type === 'gear') {
                            return optional(LiftingGear::find($record->inspectable_id))?->gear_code ?? '-';
                        }

                        return '-';
                    }),
                TextColumn::make('inspection_type')
                    ->label('Tipe')
                    ->badge(),
                TextColumn::make('inspection_date')
                    ->label('Tanggal')
                    ->date(),
                TextColumn::make('valid_until')
                    ->label('Berlaku')
                    ->date(),
                BadgeColumn::make('result')
                    ->colors([
                        'success' => 'pass',
                        'danger' => 'fail',
                        'warning' => 'conditional',
                    ]),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // add filters later if needed
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

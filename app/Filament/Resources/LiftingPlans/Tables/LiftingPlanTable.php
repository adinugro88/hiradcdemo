<?php

namespace App\Filament\Resources\LiftingPlans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LiftingPlanTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('document_number')
                    ->label('Doc No')
                    ->searchable(),
                TextColumn::make('project.name')
                    ->label('Project')
                    ->searchable(),
                TextColumn::make('plan_date')
                    ->label('Tanggal')
                    ->date(),
                BadgeColumn::make('lifting_type')
                    ->label('Tipe')
                    ->colors([
                        'warning' => 'critical',
                        'info' => 'complex',
                        'success' => 'routine',
                    ]),
                BadgeColumn::make('status')
                    ->colors([
                        'gray' => 'draft',
                        'info' => 'submitted',
                        'success' => 'approved',
                        'danger' => 'rejected',
                        'secondary' => 'closed',
                    ]),
                TextColumn::make('maximum_load_ton')
                    ->label('Max Load (ton)'),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // add filters if required
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

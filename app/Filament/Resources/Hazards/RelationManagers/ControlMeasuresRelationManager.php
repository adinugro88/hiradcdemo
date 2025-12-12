<?php

namespace App\Filament\Resources\Hazards\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ControlMeasuresRelationManager extends RelationManager
{
    protected static string $relationship = 'controlMeasures';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('basic_measure')
                ->required()
                ->columnSpanFull(),
                Textarea::make('advanced_measure')
                ->columnSpanFull(),
                Select::make('opportunity_id')
                    ->relationship('opportunity', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        \Filament\Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('description')
                            ->columnSpanFull(),
                    ])
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('basic_measure')
            ->columns([
                TextColumn::make('basic_measure')
                ->limit(50)
                ->searchable(),
                TextColumn::make('advanced_measure')
                ->limit(50)
                ->searchable(),
                TextColumn::make('opportunity.name')
                    ->label('Opportunity')
                    ->searchable()
                    ->sortable(),
                ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
                // AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                // DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Hazards\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RiskAssessmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'riskAssessments';

    protected static ?string $title = 'Risk Assessments';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Risk Information')
                    ->schema([
                        Textarea::make('description')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make('Risk Evaluation')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Fieldset::make('Before Mitigation')
                                    ->schema([
                                        TextInput::make('probability_before')
                                            ->required()
                                            ->numeric()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                $total = $state * ($get('severity_before') ?? 0);
                                                $set('total_before', $total);

                                                self::setCategory($total, $set, 'category_before');
                                            }),

                                        TextInput::make('severity_before')
                                            ->required()
                                            ->numeric()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                $total = $state * ($get('probability_before') ?? 0);
                                                $set('total_before', $total);

                                                self::setCategory($total, $set, 'category_before');
                                            }),

                                        TextInput::make('total_before')
                                            ->required()
                                            ->numeric()
                                            ->disabled()
                                            ->dehydrated(),

                                        TextInput::make('category_before')
                                            ->required()
                                            ->disabled()
                                            ->dehydrated(),
                                    ]),

                                Fieldset::make('After Mitigation')
                                    ->schema([
                                        TextInput::make('probability_after')
                                            ->required()
                                            ->numeric()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                $total = $state * ($get('severity_after') ?? 0);
                                                $set('total_after', $total);

                                                self::setCategory($total, $set, 'category_after');
                                            }),

                                        TextInput::make('severity_after')
                                            ->required()
                                            ->numeric()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                $total = $state * ($get('probability_after') ?? 0);
                                                $set('total_after', $total);

                                                self::setCategory($total, $set, 'category_after');
                                            }),

                                        TextInput::make('total_after')
                                            ->required()
                                            ->numeric()
                                            ->disabled()
                                            ->dehydrated(),

                                        TextInput::make('category_after')
                                            ->required()
                                            ->disabled()
                                            ->dehydrated(),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                TextColumn::make('description')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('total_before')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('category_before')
                    ->searchable(),
                TextColumn::make('total_after')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('category_after')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    private static function setCategory(int $total, callable $set, string $field): void
    {
        if ($total == 1) {
            $set($field, 'Sangat Kecil');
        } elseif ($total < 6) {
            $set($field, 'Kecil');
        } elseif ($total < 10) {
            $set($field, 'Sedang');
        } elseif ($total <= 15) {
            $set($field, 'Tinggi');
        } elseif ($total > 15) {
            $set($field, 'Sangat Tinggi');
        } else {
            $set($field, 'Sangat Tinggi');
        }
    }
}

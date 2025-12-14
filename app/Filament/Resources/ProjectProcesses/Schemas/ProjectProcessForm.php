<?php

namespace App\Filament\Resources\ProjectProcesses\Schemas;

use App\Models\ControlMeasure;
use App\Models\Work;
use App\Models\Hazard;
use App\Models\RiskAssessment;
use App\Models\ControlMeasures;
use App\Models\Regulation;
use App\Models\Regulations;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class ProjectProcessForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            /* =====================
             * PROJECT & PROCESS
             * ===================== */
            Select::make('project_id')
                ->relationship('project', 'name')
                ->searchable()
                ->preload()
                ->required(),

            TextInput::make('process')
                ->label('Process Name')
                ->required()
                ->maxLength(255),

            /* =====================
             * WORK
             * ===================== */
            CheckboxList::make('works')
                ->label('Work')
                ->options(fn () => Work::pluck('name', 'id'))
                ->columns(2)
                ->reactive(),

            /* =====================
             * HAZARD
             * ===================== */
            CheckboxList::make('hazards')
                ->label('Hazard')
                ->visible(fn ($get) => filled($get('works')))
                ->options(fn ($get) =>
                    Hazard::whereIn('work_id', $get('works') ?? [])
                        ->pluck('name', 'id')
                )
                ->columns(2)
                ->reactive(),

            /* =====================
             * RISK ANALYSIS
             * ===================== */
            Repeater::make('risks')
                ->label('Risk Analysis')
                ->visible(fn ($get) => filled($get('hazards')))
                ->schema([
                    Select::make('risk_assessment_id')
                        ->label('Risk')
                        ->options(fn ($get) =>
                            RiskAssessment::whereIn(
                                'hazard_id',
                                $get('../../hazards') ?? []
                            )->pluck('description', 'id')
                        )
                        ->searchable()
                        ->required(),

                    Grid::make(4)->schema([
                        TextInput::make('probability')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(5)
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($set, $get) =>
                                self::calculateRisk($set, $get)
                            ),

                        TextInput::make('severity')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(5)
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($set, $get) =>
                                self::calculateRisk($set, $get)
                            ),

                        TextInput::make('total_value')
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('category')
                            ->disabled()
                            ->dehydrated(),
                    ]),
                ]),

            /* =====================
             * CONTROL MEASURES
             * ===================== */
            Repeater::make('control_risks')
                ->label('Control Measures')
                ->visible(fn ($get) => filled($get('hazards')))
                ->schema([
                    Select::make('control_measures_id')
                        ->label('Control')
                        ->options(fn () =>
                            ControlMeasure::pluck('basic_measure', 'id')
                        )
                        ->searchable()
                        ->required(),

                    Grid::make(4)->schema([
                        TextInput::make('probability')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(5)
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($set, $get) =>
                                self::calculateRisk($set, $get)
                            ),

                        TextInput::make('severity')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(5)
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($set, $get) =>
                                self::calculateRisk($set, $get)
                            ),

                        TextInput::make('total_value')
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('category')
                            ->disabled()
                            ->dehydrated(),
                    ]),
                ]),

            /* =====================
             * REGULATIONS
             * ===================== */
            CheckboxList::make('regulations')
                ->label('Regulations')
                ->visible(fn ($get) => filled($get('hazards')))
                ->options(fn () =>
                    Regulation::pluck('title', 'id')
                )
                ->columns(2),
        ]);
    }

    protected static function calculateRisk($set, $get): void
    {
        $p = (int) ($get('probability') ?? 0);
        $s = (int) ($get('severity') ?? 0);

        $total = $p * $s;
        $set('total_value', $total);

        match (true) {
            $total === 1       => $set('category', 'Sangat Kecil'),
            $total < 6         => $set('category', 'Kecil'),
            $total < 10        => $set('category', 'Sedang'),
            $total <= 15       => $set('category', 'Tinggi'),
            default            => $set('category', 'Sangat Tinggi'),
        };
    }
}

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

            Repeater::make('work_items')
                ->label('Work & Hazard Analysis')
                ->columnSpan('full')
                ->schema([
                    Select::make('work_id')
                        ->label('Work')
                        ->options(Work::pluck('name', 'id'))
                        ->reactive()
                        ->required(),

                    // === HAZARD SUBSECTION ===
                    Repeater::make('hazard_items')
                        ->label('Hazards')
                        ->visible(fn($get) => filled($get('work_id')))
                        ->schema([
                            Select::make('hazard_id')
                                ->label('Hazard')
                                ->options(
                                    fn($get) =>
                                    Hazard::where('work_id', $get('../../work_id'))
                                        ->pluck('name', 'id')
                                )
                                ->reactive()
                                ->required(),

                            // === RISK & CONTROL IN ONE ROW ===
                            Grid::make(2) // 2 kolom dalam 1 baris
                                ->schema([

                                    // ⬅️ Kolom Kiri: RISK
                                    Repeater::make('risks')
                                        ->label('Risk Assessment')
                                        ->visible(fn($get) => filled($get('hazard_id')))
                                        ->schema([
                                            Select::make('risk_assessment_id')
                                                ->label('Risk')
                                                ->options(
                                                    fn($get) =>
                                                    RiskAssessment::where('hazard_id', $get('../../hazard_id'))
                                                        ->pluck('description', 'id')
                                                )
                                                ->required(),

                                            Grid::make(4)->schema([
                                                TextInput::make('probability')->numeric()->reactive()
                                                    ->afterStateUpdated(fn($set, $get) => ProjectProcessForm::calculateRisk($set, $get)),
                                                TextInput::make('severity')->numeric()->reactive()
                                                    ->afterStateUpdated(fn($set, $get) => ProjectProcessForm::calculateRisk($set, $get)),
                                                TextInput::make('total_value')->disabled(),
                                                TextInput::make('category')->disabled(),
                                            ]),
                                        ]),

                                    // ➡️ Kolom Kanan: CONTROL
                                    Repeater::make('control_measures')
                                        ->label('Control Measures')
                                        ->schema([
                                            Select::make('control_measures_id')
                                                ->label('Control')
                                                ->options(ControlMeasure::pluck('basic_measure', 'id'))
                                                ->required(),

                                            Grid::make(4)->schema([
                                                TextInput::make('probability')->numeric()->reactive()
                                                    ->afterStateUpdated(fn($set, $get) => ProjectProcessForm::calculateRisk($set, $get)),
                                                TextInput::make('severity')->numeric()->reactive()
                                                    ->afterStateUpdated(fn($set, $get) => ProjectProcessForm::calculateRisk($set, $get)),
                                                TextInput::make('total_value')->disabled(),
                                                TextInput::make('category')->disabled(),
                                            ]),
                                        ]),
                                ]),

                            // === REGULATIONS INSIDE HAZARD ===
                            CheckboxList::make('regulations')
                                ->label('Regulations')
                                ->options(Regulation::pluck('title', 'id')),
                        ]),
                ])
                ->addActionLabel('➕ Tambah Work'),
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

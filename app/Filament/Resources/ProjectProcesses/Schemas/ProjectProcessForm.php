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
use Filament\Forms\Components\ViewField;

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

            ViewField::make('layout')
                ->view('forms.project-process.work-item')
                ->columnSpanFull(),
            
            // Repeater::make('work_items')
            //     ->label('Work & Hazard Analysis')
            //     ->columnSpanFull()
            //     ->schema([
            //         ViewField::make('layout')
            //             ->view('forms.project-process.work-item'),
            //     ])

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

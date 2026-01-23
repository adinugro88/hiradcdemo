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
use Filament\Forms\Components\Hidden;
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
                ->hiddenOn('edit')
                ->required(),
            
            TextInput::make('project_name_view')
                ->label('Project')
                ->disabled()
                ->dehydrated(false)
                ->visibleOn('edit')
                ->afterStateHydrated(fn ($component, $record) => $component->state($record->project->name ?? '-')),

            Select::make('process')
                ->label('Process Name')
                ->disabledOn('edit')
                ->options(\App\Models\MasterProcess::pluck('name', 'name'))
                ->searchable()
                ->preload()
                ->createOptionForm([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                ])
                ->createOptionUsing(function (array $data) {
                    return \App\Models\MasterProcess::create($data)->name;
                })
                ->live()
                ->afterStateUpdated(function ($state, $set, $get, $component) {
                    if (!$state) return;
                    
                    $masterProcess = \App\Models\MasterProcess::with('works')->where('name', $state)->first();
                    
                    if ($masterProcess) {
                        $currentData = json_decode($get('_risk_control_data') ?? '{}', true);
                        if (!is_array($currentData)) $currentData = [];
                        
                        $works = [];
                        foreach ($masterProcess->works as $work) {
                            $works[$work->id] = true;
                        }
                        
                        $currentData['works'] = $works;
                        $jsonData = json_encode($currentData);
                        
                        $set('_risk_control_data', $jsonData);
                        
                        $component->getLivewire()->dispatch('risk-data-updated', data: $jsonData);
                    }
                })
                ->required(),

            Hidden::make('_risk_control_data')
                ->dehydrated()
                ->default('{}'),

            ViewField::make('layout')
                ->view('forms.project-process.work-item')
                ->columnSpanFull(),
            
            //fill width approvals
            \Filament\Schemas\Components\Section::make('Approvals')
                ->schema([
                    \Filament\Schemas\Components\Grid::make(2)
                        ->schema([
                            Select::make('prepared_by')
                                ->label('Dibuat Oleh (HSE Officer)')
                                ->options(\App\Models\User::pluck('name', 'name'))
                                ->searchable()
                                ->preload(),
                            Select::make('checked_by')
                                ->label('Diperiksa Oleh (Koord HSE)')
                                ->options(\App\Models\User::pluck('name', 'name'))
                                ->searchable()
                                ->preload(),
                            Select::make('approved_by')
                                ->label('Disetujui Oleh (Project Manager)')
                                ->options(\App\Models\User::pluck('name', 'name'))
                                ->searchable()
                                ->preload(),
                            Select::make('acknowledged_by')
                                ->label('Diketahui Oleh (Client ACS)')
                                ->options(\App\Models\User::pluck('name', 'name'))
                                ->searchable()
                                ->preload(),
                        ]),
                ])
                ->columnSpanFull(),

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

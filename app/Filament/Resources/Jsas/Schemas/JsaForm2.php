<?php

namespace App\Filament\Resources\Jsas\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class JsaForm2
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema([
                // INFORMASI PROYEK
                TextInput::make('project_name')
                    ->label('Nama Proyek')
                    ->required()
                    ->columnSpanFull(),

                Select::make('project_id')
                    ->label('Proyek')
                    ->relationship('project', 'name')
                    ->searchable()
                    ->preload()
                    ->columnSpanFull(),

                Select::make('supervisor_id')
                    ->label('Dibuat Oleh (Supervisor)')
                    ->relationship('supervisor', 'name')
                    ->searchable()
                    ->preload()
                    ->columnSpanFull(),

                Select::make('site_manager_id')
                    ->label('Site Manager')
                    ->relationship('siteManager', 'name')
                    ->searchable()
                    ->preload()
                    ->columnSpanFull(),

                Select::make('leader_hse_id')
                    ->label('Leader HSE Proyek')
                    ->relationship('leaderHse', 'name')
                    ->searchable()
                    ->preload()
                    ->columnSpanFull(),

                Select::make('project_manager_id')
                    ->label('Project Manager')
                    ->relationship('projectManager', 'name')
                    ->searchable()
                    ->preload()
                    ->columnSpanFull(),

                DatePicker::make('created_date')
                    ->label('Tanggal Pembuatan')
                    ->required()
                    ->columnSpanFull(),

                // NAMA PEKERJAAN
                Select::make('work_id')
                    ->label('Nama Pekerjaan')
                    ->options(\App\Models\ProjectProcess::pluck('process', 'id'))
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(fn($set) => $set('work_sequence', []))
                    ->columnSpanFull(),

                // URUTAN PEKERJAAN
                Repeater::make('work_sequence')
                    ->label('Urutan Pekerjaan')
                    ->schema([
                        Select::make('work_id')
                            ->label('Pekerjaan')
                            ->options(function ($get) {
                                $projectProcessId = $get('../../work_id');
                                if (! $projectProcessId) return [];
                                $workIds = \App\Models\WorkProcess::where('project_process_id', $projectProcessId)
                                    ->pluck('work_id')
                                    ->unique()
                                    ->values();
                                return \App\Models\Work::whereIn('id', $workIds)
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->searchable()
                            ->required(),
                    ])
                    ->reorderable(true)
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        $set('selected_hazards', []);
                        $set('hazard_details', []);
                    })
                    ->columnSpanFull(),

                // HAZARD CHECKLIST
                CheckboxList::make('selected_hazards')
                    ->label('Daftar Hazard')
                    ->options(function ($get) {
                        $sequence = $get('work_sequence') ?? [];
                        $workIds = collect($sequence)
                            ->pluck('work_id')
                            ->filter()
                            ->unique()
                            ->values()
                            ->all();
                        if (empty($workIds)) return [];
                        return \App\Models\Hazard::whereIn('work_id', $workIds)
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->columns(1)
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        $items = [];
                        if ($state) {
                            $hazards = \App\Models\Hazard::with([
                                'riskAssessments',
                                'controlMeasures',
                                'regulations'
                            ])->findMany($state);
                            foreach ($hazards as $hazard) {
                                $items[] = [
                                    'hazard_id' => $hazard->id,
                                    'hazard' => ['name' => $hazard->name],
                                    'work_id' => $hazard->work_id,
                                    'risks' => $hazard->riskAssessments
                                        ->map(fn($r) => [
                                            'id' => $r->id,
                                            'description' => $r->description,
                                        ])->values()->toArray(),
                                    'regulations' => $hazard->regulations
                                        ->map(fn($r) => [
                                            'id' => $r->id,
                                            'title' => $r->title,
                                            'reference_number' => $r->reference_number,
                                            'description' => $r->description,
                                        ])->values()->toArray(),
                                    'controls' => $hazard->controlMeasures
                                        ->map(fn($c) => [
                                            'id' => $c->id,
                                            'basic_measure' => $c->basic_measure,
                                            'advanced_measure' => $c->advanced_measure,
                                        ])->values()->toArray(),
                                    'confirmed_sections' => [],
                                ];
                            }
                        }
                        $set('hazard_details', $items);
                    })
                    ->columnSpanFull(),

                // DETAIL HAZARD
                Repeater::make('hazard_details')
                    ->label('Detail Hazard Terpilih')
                    ->schema([
                        Hidden::make('hazard_id'),
                        Placeholder::make('hazard_name_display')
                            ->label('Hazard')
                            ->content(fn($get) => $get('hazard.name') ?? '-')
                            ->columnSpanFull(),
                        CheckboxList::make('confirmed_sections')
                            ->label('Poin-Poin yang Harus Dikonfirmasi')
                            ->options(function ($get) {
                                $options = [];
                                foreach (($get('risks') ?? []) as $risk) {
                                    $options["risk_{$risk['id']}"] = "Risk Assessment: " . ($risk['description'] ?: '-');
                                }
                                foreach (($get('regulations') ?? []) as $reg) {
                                    $options["regulation_{$reg['id']}"] = "Regulation: " . ($reg['title'] ?: 'Tanpa judul') . " (" . ($reg['reference_number'] ?: 'Tanpa nomor') . ") - " . ($reg['description'] ?: 'Tidak ada deskripsi.');
                                }
                                foreach (($get('controls') ?? []) as $control) {
                                    $label = "Control Measure: " . ($control['basic_measure'] ?: '-');
                                    if (!empty($control['advanced_measure'])) {
                                        $label .= " | Advanced: {$control['advanced_measure']}";
                                    }
                                    $options["control_{$control['id']}"] = $label;
                                }
                                return $options;
                            })
                            ->columns(1)
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->addable(false)
                    ->deletable(false)
                    ->cloneable(false)
                    ->defaultItems(0)
                    ->hiddenLabel()
                    ->visible(fn($get) => count($get('selected_hazards') ?? []) > 0)
                    ->columnSpanFull(),
            ]);
    }
}

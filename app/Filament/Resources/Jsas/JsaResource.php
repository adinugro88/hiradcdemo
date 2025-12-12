<?php

namespace App\Filament\Resources\Jsas;

use App\Filament\Resources\Jsas\Pages;
use App\Models\Jsa;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

// Form components
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;

class JsaResource extends Resource
{
    protected static ?string $model = Jsa::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentText;

    public static function form(Schema $schema): Schema
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
                    ->options(\App\Models\Work::pluck('name', 'id'))
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(fn ($set) => $set('selected_hazards', []))
                    ->columnSpanFull(),

                // HAZARD CHECKLIST
                CheckboxList::make('selected_hazards')
                    ->label('Daftar Hazard')
                    ->options(function ($get) {
                        if (!$get('work_id')) return [];
                        return \App\Models\Hazard::where('work_id', $get('work_id'))
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->columns(1)
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        $items = [];

                        if ($state) {
                            // ✅ GUNAKAN NAMA RELASI YANG BENAR (plural)
                            $hazards = \App\Models\Hazard::with([
                                'riskAssessments',
                                'controlMeasures',
                                'regulations'
                            ])->findMany($state);

                            foreach ($hazards as $hazard) {
                                // ✅ AMBIL ENTRI PERTAMA DARI KOLEKSI
                                $risk = $hazard->riskAssessments->first();
                                $control = $hazard->controlMeasures->first();
                                $regulation = $hazard->regulations->first();

                                $items[] = [
                                    'hazard_id' => $hazard->id,
                                    'hazard' => ['name' => $hazard->name],
                                    'risk' => [
                                        'description' => $risk?->description,
                                    ],
                                    'control' => [
                                        'basic_measure' => $control?->basic_measure,
                                        'opportunity_measure' => $control?->opportunity_measure,
                                    ],
                                    'regulation' => [
                                        'title' => $regulation?->title,
                                        'reference_number' => $regulation?->reference_number,
                                        'description' => $regulation?->description,
                                    ],
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
                            ->content(fn ($get) => $get('hazard.name') ?? '-')
                            ->columnSpanFull(),

                        CheckboxList::make('confirmed_sections')
                            ->label('Poin-Poin yang Harus Dikonfirmasi')
                            ->options(function ($get) {
                                return [
                                    'risk' => 'Risk Assessment: ' . ($get('risk.description') ?: 'Tidak ada deskripsi risiko.'),
                                    'regulation' => 'Regulation: ' . (
                                        ($get('regulation.title') ?: 'Tanpa judul') .
                                        ' (' . ($get('regulation.reference_number') ?: 'Tanpa nomor') . ') - ' .
                                        ($get('regulation.description') ?: 'Tidak ada deskripsi.')
                                    ),
                                    'control' => 'Control Measure: ' . ($get('control.basic_measure') ?: 'Tidak ada control measure.'),
                                    'opportunity' => 'Opportunity: ' . ($get('control.opportunity_measure') ?: 'Tidak ada opportunity measure.'),
                                ];
                            })
                            ->default([])
                            ->columns(1)
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->addable(false)
                    ->deletable(false)
                    ->cloneable(false)
                    ->defaultItems(0)
                    ->hiddenLabel()
                    ->visible(fn ($get) => count($get('selected_hazards') ?? []) > 0)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('project_name')->label('Nama Proyek'),
                TextColumn::make('work.name')->label('Nama Pekerjaan'),
                TextColumn::make('created_date')->label('Tanggal Pembuatan')->date(),
                TextColumn::make('steps_count')
                    ->label('Jumlah Langkah')
                    ->getStateUsing(fn ($record) => $record->steps()->count()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListJsas::route('/'),
            'create' => Pages\CreateJsa::route('/create'),
            'edit'   => Pages\EditJsa::route('/{record}/edit'),
        ];
    }
}
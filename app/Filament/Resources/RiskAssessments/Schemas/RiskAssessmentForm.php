<?php

namespace App\Filament\Resources\RiskAssessments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RiskAssessmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('hazard_id')
                    ->relationship('hazard', 'name')
                    ->required(),

                // BEFORE MITIGATION
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


                // AFTER MITIGATION
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
            ]);
    }

    /**
     * Helper menentukan kategori otomatis.
     */
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

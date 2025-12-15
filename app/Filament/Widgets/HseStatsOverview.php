<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use App\Models\work;
use App\Models\LiftingGear;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class HseStatsOverview extends BaseWidget
{
    public function getStats(): array
    {
        return [
            Stat::make('Total Project', Project::count())
            ->icon('heroicon-m-rectangle-stack'),
            Stat::make('Total HIRADC', work::count())
            ->icon('heroicon-o-rectangle-stack'),
            Stat::make('Total Equipment', LiftingGear::count())
            ->icon('heroicon-m-truck'), 
        ];
    }
}

<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\HseStatsOverview;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            HseStatsOverview::class,
        ];
    }
}

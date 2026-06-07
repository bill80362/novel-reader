<?php

namespace App\Filament\Widgets;

use App\Models\Chapter;
use App\Models\Novel;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class NovelStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('總瀏覽數', number_format((int) Novel::sum('view_count')))
                ->description('所有小說累計'),
            Stat::make('已發佈小說', Novel::whereIn('status', ['published', 'completed'])->count())
                ->description('已發佈 + 已完結'),
            Stat::make('章節總數', Chapter::count()),
        ];
    }
}

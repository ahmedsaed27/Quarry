<?php

namespace App\Filament\Widgets\Supply;

use App\Models\Supply;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    private $model = Supply::class;

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        return [
            Stat::make('تحميل اليوم', $this->model::whereDate('created_at' , date('Y-m-d'))->count())
            ->description('32k increase')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->color('success'),

            Stat::make('اجمالي عدد الفواتير', $this->model::where('status' , 'Invoiced')->count())
            ->description('32k increase')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->chart([7, 2, 10, 3, 15, 4, 17])

            ->color('success'),

            Stat::make('اجمالي عدد الفواتير المحصله جزئيا', $this->model::where('status' , 'PartialCollection')->count())
            ->description('32k increase')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->chart([17,13,15,10,6,11,4])

            ->color('danger'),
        ];
    }
}

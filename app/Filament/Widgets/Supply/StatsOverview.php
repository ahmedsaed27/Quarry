<?php

namespace App\Filament\Widgets\Supply;

use App\Models\Supply;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    private $model = Supply::class;

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        return [
            Stat::make('تحميل اليوم', $this->model::whereDate('date' , date('Y-m-d'))->count())
            ->chart([12 ,7, 16 , 11 , 15 , 14])
            ->color('success'),

            Stat::make('اجمالي عدد الفواتير', $this->model::where('status' , 'Invoiced')->count())
            ->chart([12 ,7, 16 , 11 , 15 , 14])
            ->color('success'),

            Stat::make('اجمالي عدد الفواتير المستلمه', $this->model::where('status' , 'delivered')->count())
            ->chart([12 ,7, 16 , 11 , 15 , 14])
            ->color('success'),
        ];
    }


}

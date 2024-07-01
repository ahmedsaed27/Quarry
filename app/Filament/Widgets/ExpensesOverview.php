<?php

namespace App\Filament\Widgets;

use App\Models\Expenses;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ExpensesOverview extends BaseWidget
{
    private $model = Expenses::class;

    protected static bool $isLazy = false;

    protected function getStats(): array
    {

        // dd($ids = $this->model::pluck('id')->flatten()->toArray());
        return [
            Stat::make('مصروفات غير مدفوعه', $this->model::where('isPaymentMade' , false)->count())
            ->chart($this->model::pluck('id')->flatten()->toArray())
            ->color('danger'),

            Stat::make('اجمالي مصروفات الشهر', $this->model::whereBetween('date', [
                Carbon::now()->startOfMonth()->toDateString(),
                Carbon::now()->endOfMonth()->toDateString(),
            ])->sum('expense')),

            Stat::make('اجمالي مصروفات اليوم', $this->model::whereDate('date' , date('Y-m-d'))->sum('expense')),
        ];
    }
}

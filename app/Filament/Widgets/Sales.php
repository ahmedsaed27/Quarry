<?php

namespace App\Filament\Widgets;

use App\Models\Supply;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class Sales extends ChartWidget
{
    protected static ?string $heading = 'المبيعات';

    protected static bool $isLazy = false;

    private $model = Supply::class;

    protected function getData(): array
    {
         $currentYear = Carbon::now()->year;

         $sales = $this->model::whereYear('date', $currentYear)
            ->selectRaw('MONTH(date) as month, SUM(total_invoice) as total')
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        $monthlySale = array_fill(1, 12, 0);

        foreach ($sales as $month => $data) {
            $monthlySale[$month] = $data->total;
        }

        return [
            'datasets' => [
                [
                    'label' => 'فواتير بقيمت',
                    'data' => array_values($monthlySale),
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

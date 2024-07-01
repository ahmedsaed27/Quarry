<?php

namespace App\Filament\Widgets;

use App\Models\Expenses as ExpensesModel;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class Expenses extends ChartWidget
{
    protected static ?string $heading = 'المصاريف';

    protected static bool $isLazy = false;

    private $model = ExpensesModel::class;

    protected function getData(): array
    {
        // Get the current year
        $currentYear = Carbon::now()->year;

        // Query to get total expenses per month for the current year using Eloquent ORM
        $expenses = $this->model::whereYear('date', $currentYear)
            ->selectRaw('MONTH(date) as month, SUM(expense) as total')
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        // Initialize an array with 12 months of 0 expenses
        $monthlyExpenses = array_fill(1, 12, 0);

        // Fill in the actual expenses
        foreach ($expenses as $month => $expense) {
            $monthlyExpenses[$month] = $expense->total;
        }

        // Prepare the data for the chart
        $datasets = [
            [
                'label' => 'مصاريف بقيمت',
                'data' => array_values($monthlyExpenses),
            ],
        ];

        $months  = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        return [
            'datasets' => $datasets,
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}

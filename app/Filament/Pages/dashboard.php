<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\Expenses;
use App\Filament\Widgets\Sales;
use App\Filament\Widgets\Supply\StatsOverview;
use Filament\Pages\Page;
use Filament\Pages\Dashboard as filamentDashboard;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;

class dashboard extends filamentDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dashboard';


    protected function getHeaderWidgets(): array
    {
        return [
            AccountWidget::class,
            FilamentInfoWidget::class,
            StatsOverview::class,
            Sales::class,
            Expenses::class,

        ];
    }

}

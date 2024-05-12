<?php

namespace App\Filament\Resources\SupplyResource\Pages;

use App\Filament\Resources\SupplyResource;
use App\Filament\Widgets\Supply\StatsOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListSupplies extends ListRecords
{
    protected static string $resource = SupplyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverview::class
        ];
    }


    public function getTabs(): array
    {
        return [
            null => Tab::make('All')->label('الكل'),
            'مفوتره' => Tab::make()->query(fn ($query) => $query->where('status', 'Invoiced')),
            'تم التحصيل' => Tab::make()->query(fn ($query) => $query->where('status', 'Collected')),
            'تحصيل جزئي' => Tab::make()->query(fn ($query) => $query->where('status', 'PartialCollection')),
            'جاري الشحن' => Tab::make()->query(fn ($query) => $query->where('status', 'shipped')),
            'تم الاستلام' => Tab::make()->query(fn ($query) => $query->where('status', 'delivered')),
            'تم الالغاء' => Tab::make()->query(fn ($query) => $query->where('status', 'cancelled')),
        ];
    }
}

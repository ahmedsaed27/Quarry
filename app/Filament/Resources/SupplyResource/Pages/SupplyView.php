<?php

namespace App\Filament\Resources\SupplyResource\Pages;

use App\Filament\Resources\SupplyResource;
use App\Filament\Widgets\InvoicePayments;
use Filament\Resources\Pages\ViewRecord;

class SupplyView extends ViewRecord
{
    protected static string $resource = SupplyResource::class;

    protected static string $view = 'test';


    protected function getFooterWidgets(): array
    {
        return [
            InvoicePayments::make([
                'recordId' => $this->record->id
            ])
        ];
    }
}

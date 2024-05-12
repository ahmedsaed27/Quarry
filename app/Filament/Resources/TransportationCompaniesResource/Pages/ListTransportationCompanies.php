<?php

namespace App\Filament\Resources\TransportationCompaniesResource\Pages;

use App\Filament\Resources\TransportationCompaniesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransportationCompanies extends ListRecords
{
    protected static string $resource = TransportationCompaniesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

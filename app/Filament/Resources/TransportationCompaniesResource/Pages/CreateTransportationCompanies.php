<?php

namespace App\Filament\Resources\TransportationCompaniesResource\Pages;

use App\Filament\Resources\TransportationCompaniesResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTransportationCompanies extends CreateRecord
{
    protected static string $resource = TransportationCompaniesResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

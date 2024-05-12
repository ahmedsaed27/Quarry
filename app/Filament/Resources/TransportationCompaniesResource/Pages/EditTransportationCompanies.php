<?php

namespace App\Filament\Resources\TransportationCompaniesResource\Pages;

use App\Filament\Resources\TransportationCompaniesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransportationCompanies extends EditRecord
{
    protected static string $resource = TransportationCompaniesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

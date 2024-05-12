<?php

namespace App\Filament\Resources\TransportWorkersResource\Pages;

use App\Filament\Resources\TransportWorkersResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTransportWorkers extends CreateRecord
{
    protected static string $resource = TransportWorkersResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

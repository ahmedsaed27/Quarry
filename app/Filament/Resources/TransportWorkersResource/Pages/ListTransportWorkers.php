<?php

namespace App\Filament\Resources\TransportWorkersResource\Pages;

use App\Filament\Resources\TransportWorkersResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransportWorkers extends ListRecords
{
    protected static string $resource = TransportWorkersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\TransportWorkersResource\Pages;

use App\Filament\Resources\TransportWorkersResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransportWorkers extends EditRecord
{
    protected static string $resource = TransportWorkersResource::class;

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

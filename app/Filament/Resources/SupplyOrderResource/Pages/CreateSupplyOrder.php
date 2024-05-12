<?php

namespace App\Filament\Resources\SupplyOrderResource\Pages;

use App\Filament\Resources\SupplyOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSupplyOrder extends CreateRecord
{
    protected static string $resource = SupplyOrderResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

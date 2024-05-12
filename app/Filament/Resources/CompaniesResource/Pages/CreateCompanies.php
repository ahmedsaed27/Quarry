<?php

namespace App\Filament\Resources\CompaniesResource\Pages;

use App\Filament\Resources\CompaniesResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCompanies extends CreateRecord
{
    protected static string $resource = CompaniesResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

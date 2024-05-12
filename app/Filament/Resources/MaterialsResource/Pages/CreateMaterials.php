<?php

namespace App\Filament\Resources\MaterialsResource\Pages;

use App\Filament\Resources\MaterialsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMaterials extends CreateRecord
{
    protected static string $resource = MaterialsResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

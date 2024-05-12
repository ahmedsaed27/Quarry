<?php

namespace App\Filament\Resources\MaterialsResource\Pages;

use App\Filament\Resources\MaterialsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMaterials extends EditRecord
{
    protected static string $resource = MaterialsResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

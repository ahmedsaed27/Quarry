<?php

namespace App\Filament\Resources\QuarriesResource\Pages;

use App\Filament\Resources\QuarriesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuarries extends EditRecord
{
    protected static string $resource = QuarriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    // protected function getRedirectUrl(): string
    // {
    //     return $this->getResource()::getUrl('index');
    // }
}

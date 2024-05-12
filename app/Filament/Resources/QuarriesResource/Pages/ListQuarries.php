<?php

namespace App\Filament\Resources\QuarriesResource\Pages;

use App\Filament\Resources\QuarriesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuarries extends ListRecords
{
    protected static string $resource = QuarriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

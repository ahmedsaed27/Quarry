<?php

namespace App\Filament\Resources\QuarriesResource\Pages;

use App\Filament\Resources\QuarriesResource;
use Exception;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateQuarries extends CreateRecord
{
    protected static string $resource = QuarriesResource::class;

    // protected function getRedirectUrl(): string
    // {
    //     return $this->getResource()::getUrl('index');
    // }
}

<?php

namespace App\Filament\Resources\ExpensesResource\Pages;

use App\Filament\Resources\ExpensesResource;
use App\Models\Supply;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateExpenses extends CreateRecord
{
    protected static string $resource = ExpensesResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        if($data['expense_type'] == 'cleanliness'){
            $workersCost = $data['workers_number'] * $data['workers_hours_number'] * $data['workers_hourly_price'];
            $trucksCost = $data['trucks_number'] * $data['trucks_hours_number'] * $data['trucks_hourly_price'];

            $data['expense'] = $workersCost + $trucksCost + $data['transportation_expenses'];
        }

        if($data['expense_type'] == 'materialsTransportation'){
            $supply = Supply::find($data['supply_id']);
            $data['expense'] = (($supply->cost_of_transporting_a_ton * $supply->ton) - $supply->opening_amount);
        }

        return $data;
    }
}

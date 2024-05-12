<?php

namespace App\Filament\Resources\SupplyResource\Pages;

use App\Filament\Resources\SupplyResource;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Actions\Action;


class CreateSupply extends CreateRecord
{
    protected static string $resource = SupplyResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


    protected function afterCreate(): void
    {
        $order = $this->record;

        Notification::make()
            ->title('التوريدات')
            ->icon('heroicon-m-truck')
            ->body("تم اضافه توريد جديد")
            ->actions([
                Action::make('View')
                    ->label('ذهاب')
                    ->url(SupplyResource::getUrl('edit', ['record' => $order])),
            ])
            ->sendToDatabase(User::all());
    }


}

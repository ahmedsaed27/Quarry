<?php

namespace App\Filament\Widgets;

use App\Models\Supply;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;
use Filament\Support\Enums\IconPosition;



class InvoicePayments extends BaseWidget
{
    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 2;

    protected static ?string $heading = 'تفاصيل نقديه';

    public $recordId;

    public function table(Table $table): Table
    {
        return $table
            ->query(Supply::query()->where('id' , $this->recordId))
            ->columns([
                TextColumn::make('ton')->label('عدد الاطنان'),

                TextColumn::make('cost_of_transporting_a_ton')->label('تكلفه نقل الطن'),

                TextColumn::make('total_of_transporting')->label('اجمالي النقل')->default(function(Model $record){
                    return $record->cost_of_transporting_a_ton * $record->ton;
                })
                ->icon('heroicon-m-currency-dollar')
                ->iconPosition(IconPosition::After)
                ->color('danger'),

                TextColumn::make('price_per_ton')->label('سعر الخامه'),

                TextColumn::make('total_of_material')->label('اجمالي سعر الخامه')->default(function(Model $record){
                    return $record->price_per_ton * $record->ton;
                })
                ->icon('heroicon-m-currency-dollar')
                ->iconPosition(IconPosition::After)
                ->color('danger'),


                TextColumn::make('total_invoice')->default(function(Model $record){
                    return $record->total_invoice ?? 'لم يتم الوزن بعد';
                })->label('اجمالي الفاتوره')
                ->icon('heroicon-m-currency-dollar')
                ->iconPosition(IconPosition::After)
                ->color('success'),
            ]);
    }
}

<?php

namespace App\Filament\Resources;

use App\Enums\Order;
use App\Filament\Resources\SupplyResource\Pages;
use App\Filament\Resources\SupplyResource\RelationManagers;
use App\Models\Companies;
use App\Models\Customers;
use App\Models\Quarries;
use App\Models\Supply;
use App\Models\TransportationCompanies;
use App\Models\TransportWorkers;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Support\Enums\IconPosition;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Collection;

use Filament\Tables\Filters\Filter;
use Malzariey\FilamentDaterangepickerFilter\Fields\DateRangePicker;
use Carbon\Carbon;

use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section as InfolistsSection;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use App\Models\SupplyOrder;
use Illuminate\Database\Eloquent\Model;

use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\ActionGroup;

class SupplyResource extends Resource
{
    protected static ?string $model = Supply::class;

    protected static ?string $navigationLabel = 'التحميل';

    protected static ?string $pluralModelLabel = 'التحميل';

    protected static ?string $modelLabel = 'تحميل';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'quarrie.name',
            'materials.name',
            'transportation_companies.name',
            'transport_workers.name',
            'customers.name',
            'company.name',
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'المحجر' => $record->quarrie->name,
            'الخامه' => $record->materials->name,
            'مقاول النقل' => $record->transportation_companies->name,
            'السائق' => $record->transport_workers->name,
            'العميل' => $record->customers->name,
            'شركته' => $record->company->name,
        ];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return SupplyResource::getUrl('view', ['record' => $record]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('اضافه توريد')
                ->description('من هنا يمكنك اضافه التوريد الخاص بك')
                ->schema([

                    TextInput::make('reference')
                    ->label('رقم البوليصه')
                    ->default('OR-' . random_int(100000, 999999))
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->maxLength(32)
                    ->unique(Supply::class, 'reference', ignoreRecord: true),

                    DatePicker::make('date')->label('تاريخ الفاتوره'),


                    Select::make('quarries_id')
                    ->label('المحجر')
                    ->options(Quarries::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Set $set, ?string $state) {
                        if(is_null($state)){
                            return $set('materials_id', null);
                        }

                    }),

                    // Select::make('materials')
                    Select::make('materials_id')
                    ->label('الخامات')
                    ->options(function (Set $set , Get $get){
                        if($get('quarries_id')){
                            return Quarries::find($get('quarries_id'))->materials->pluck('name' , 'id');
                        }

                        return $set('price_per_ton', null);
                    })
                    ->live()
                    ->afterStateUpdated(function (Set $set, Get $get , ?string $state) {
                        return $set('price_per_ton',
                        Quarries::with(['materials' => function ($query) use ($get) {
                            $query->where('materials_id', $get('materials_id'));
                        }])->find($get('quarries_id'))->materials->first()->pivot->price ?? '');
                    })
                    ->searchable(),
                    // ->required(),

                    TextInput::make('price_per_ton')
                    ->label('سعر الطن اليوم بالنسبه للمحجر المختار')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, Get $get , ?string $state) {

                        if(
                            !empty($get('ton'))
                            && !is_null($get('ton'))
                            && !is_null($get('cost_of_transporting_a_ton'))
                            && !empty($get('cost_of_transporting_a_ton'))
                            && !is_null($get('profit'))
                            && !empty($get('profit'))
                        ){

                            // $companieProfit =  Companies::where('id' , $get('Company_id'))->first();

                            // $match = match ($companieProfit->profit_type) {
                            //     0 => (($companieProfit->profit_margin + $state) * $get('ton')) + ($get('cost_of_transporting_a_ton') * $get('ton')),
                            //     1 => (($get('ton') * $state) * (1 + $companieProfit->profit_margin / 100)) + ($get('cost_of_transporting_a_ton') * $get('ton')),
                            // };

                            $profit = (($get('profit') + $state) * $get('ton')) + ($get('cost_of_transporting_a_ton') * $get('ton'));

                            return $set('total_invoice', (float) $profit);
                        }

                        return $set('total_invoice', 0);
                    })
                    // ->disabled()
                    ->numeric()
                    ->required(),

                    Select::make('transportation_companies_id')
                    ->label('مقاول النقل')
                    ->options(TransportationCompanies::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Set $set, ?string $state) {
                        if(is_null($state)){
                            return $set('transport_workers_id', null);
                        }
                    }),

                    TextInput::make('cost_of_transporting_a_ton')
                    ->label('تكلفه نقل الطن')
                    ->placeholder('100')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, Get $get , ?string $state) {

                        if(
                            !empty($get('ton'))
                            && !is_null($get('ton'))
                            && !is_null($get('price_per_ton'))
                            && !empty($get('price_per_ton'))
                            && !is_null($get('profit'))
                            && !empty($get('profit'))
                        ){

                            // $companieProfit =  Companies::where('id' , $get('Company_id'))->first();

                            // $match = match ($companieProfit->profit_type) {
                            //     0 => (($companieProfit->profit_margin + $get('price_per_ton')) * $get('ton')) + ($state * $get('ton')),
                            //     1 => (($get('ton') * $get('price_per_ton')) * (1 + $companieProfit->profit_margin / 100)) + ($state * $get('ton')),
                            // };

                            $profit = (($get('profit') + $get('price_per_ton')) * $get('ton')) + ($state * $get('ton'));

                            return $set('total_invoice', (float) $profit);
                        }

                        return $set('total_invoice', 0);
                    })
                    ->numeric()
                    ->required(),

                    Select::make('transport_workers_id')
                    ->label('السائق')
                    ->options(fn (Get $get): Collection => TransportWorkers::query()
                    ->where('transportation_companies_id', $get('transportation_companies_id'))
                    ->pluck('name', 'id'))
                    ->live()
                    ->searchable()
                    ->required(),

                    TextInput::make('opening_amount')
                    ->label('العهده')
                    ->placeholder('5000')
                    ->numeric()
                    ->required(),

                    Select::make('customers_id')
                    ->label('يتجه الي شركه')
                    ->options(Customers::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Set $set, ?string $state) {
                        if(is_null($state)){
                            $set('branch', null);
                            return $set('supply_orders_id', null);
                        }
                    }),


                    Select::make('branch')
                    ->label('الي فرع')
                    ->options(
                        fn (Get $get): Collection => Customers::query()
                        ->where('id', $get('customers_id'))
                        ->pluck('address')
                        ->flatten()
                    )
                    ->live()
                    ->searchable()
                    ->required(),

                    Select::make('supply_orders_id')
                    ->label('تابع لامر توريد رقم')
                    ->options(function (Get $get){
                        if ($get('customers_id')) {
                            return SupplyOrder::
                            where('customers_id', $get('customers_id'))
                            ->where('show' , true)
                            ->pluck('supply_number', 'id');
                        }
                    })
                    ->searchable()
                    ->required(),

                    Select::make('Company_id')
                    ->label('باسم شركه')
                    ->options(Companies::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),



                    Forms\Components\ToggleButtons::make('status')
                    ->label('حاله الفاتوره')
                    ->inline()
                    ->options(Order::class)
                    ->default(Order::Shipped)
                    ->required()
                    ->columnSpan(2),


                ])->columns(2),

                Section::make('الحسابات')
                    ->schema([
                        TextInput::make('profit')->label('هامش الربح')

                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Set $set, Get $get , ?string $state){
                            if(
                                !empty($get('ton'))
                                && !is_null($get('ton'))
                                && !is_null($get('price_per_ton'))
                                && !empty($get('price_per_ton'))
                                && !is_null($get('cost_of_transporting_a_ton'))
                                && !empty($get('cost_of_transporting_a_ton'))
                            ){

                                // $companieProfit =  Companies::where('id' , $get('Company_id'))->first();

                                // $match = match ($companieProfit->profit_type) {
                                //     0 => (($companieProfit->profit_margin + $get('price_per_ton')) * $get('ton')) + ($state * $get('ton')),
                                //     1 => (($get('ton') * $get('price_per_ton')) * (1 + $companieProfit->profit_margin / 100)) + ($state * $get('ton')),
                                // };

                                $profit = (($state + $get('price_per_ton')) * $get('ton')) + ($get('cost_of_transporting_a_ton') * $get('ton'));

                                return $set('total_invoice', (float) $profit);
                            }

                            return $set('total_invoice', 0);
                        })
                        ->numeric()
                        ->required(),

                        TextInput::make('ton')->label('الوزن')
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Set $set, Get $get , ?string $state) {
                            // $profit =  Companies::where('id' , $get('Company_id'))->first();

                            // $match = match ($profit->profit_type) {
                            //     0 => (($profit->profit_margin + $get('price_per_ton')) * $state) + ($get('cost_of_transporting_a_ton') * $state),
                            //     // 0 => ($profit->profit_margin + $get('price_per_ton')) * $state,
                            //     // 1 => ((($get('price_per_ton') * $state) * $profit->profit_margin) / 100) +  $get('price_per_ton') * $state,
                            //     1 => (($get('price_per_ton') * $state) * (1 + $profit->profit_margin / 100)) + ($get('cost_of_transporting_a_ton') * $state),
                            // };



                            $profit = (($get('profit') + $get('price_per_ton')) * $state) + ($get('cost_of_transporting_a_ton') * $state);

                            return $set('total_invoice', (float) $profit);

                        })
                        ->numeric()
                        ->required(),

                        TextInput::make('total_invoice')->label('اجمالي الفاتوره')->numeric()->disabled()->dehydrated()->required(),

                    ])->hiddenOn('create')->columns(2)
            ]);
    }



    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                ExportAction::make()->exports([
                    ExcelExport::make()->fromTable()->askForFilename(label:'اسم الملف')->askForWriterType(label:'نوع الملف'),
                ]),
            ])
            ->columns([
                TextColumn::make('id')->label('#')->searchable(),
                TextColumn::make('user.name')->label('الموظف')->searchable(),
                TextColumn::make('reference')->label('رقم البوليصه')->searchable(),
                TextColumn::make('company.name')->label('اسم شركتك')->searchable(),
                TextColumn::make('quarrie.name')->label('المحجر')->searchable(),
                TextColumn::make('transportation_companies.name')->label('مقاول النقل')->searchable(),
                TextColumn::make('transportation_companies.phone')->label('موبيل مقاول النقل')->copyable()->badge()->searchable()->toggleable(isToggledHiddenByDefault:true),
                TextColumn::make('transport_workers.name')->label('السائق')->searchable(),
                TextColumn::make('opening_amount')->label('العهده')->summarize([
                    Tables\Columns\Summarizers\Sum::make()->money('EGP'),
                ])->searchable(),
                TextColumn::make('transport_workers.phone')->label('موبيل السائق')->copyable()->badge()->searchable()->toggleable(isToggledHiddenByDefault:true),
                TextColumn::make('transport_workers.car_number')->label('رقم العربيه')->searchable()->copyable()->badge()->toggleable(isToggledHiddenByDefault:true),
                TextColumn::make('customers.name')->label('شركه المورد')->searchable(),
                TextColumn::make('customers.phone')->label('موبيل المورد')->copyable()->badge()->searchable()->toggleable(isToggledHiddenByDefault:true),
                TextColumn::make('status')->label('الحاله')->badge(),
                TextColumn::make('ton')->label('الوزن'),
                TextColumn::make('price_per_ton')->label('سعر الطن')->toggleable(isToggledHiddenByDefault:true),
                // TextColumn::make('profit_margin')->label('الربح')
                TextColumn::make('total_invoice')->label('اجمالي الفاتوره')
                ->icon('heroicon-m-currency-dollar')
                ->iconPosition(IconPosition::After)
                ->color('success')
                ->summarize([
                    Tables\Columns\Summarizers\Sum::make()->money('EGP'),
                ]),
                TextColumn::make('date')->label('تاريخ الفاتوره')->dateTime()->toggleable(isToggledHiddenByDefault:true),
                TextColumn::make('created_at')->label('وقت الانشاء')->dateTime()->toggleable(isToggledHiddenByDefault:true),
                TextColumn::make('updated_at')->label('وقت التعديل')->dateTime()->toggleable(isToggledHiddenByDefault:true)

            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('company')
                ->label('شركتك')
                ->relationship('company', 'name')
                ->options(Companies::all()->pluck('name' , 'id')),

                SelectFilter::make('quarrie')
                ->label('المحجر')
                ->relationship('quarrie', 'name')
                ->options(Companies::all()->pluck('name' , 'id')),


                SelectFilter::make('transportation_companies')
                ->label('مقاول النقل')
                ->relationship('transportation_companies', 'name')
                ->options(Companies::all()->pluck('name' , 'id')),

                SelectFilter::make('transport_workers')
                ->label('السائق')
                ->relationship('transport_workers', 'name')
                ->options(Companies::all()->pluck('name' , 'id')),

                SelectFilter::make('customers')
                ->label('شركه المورد')
                ->relationship('customers', 'name')
                ->options(Companies::all()->pluck('name' , 'id')),


                Filter::make('created_at')
                ->form([
                    DateRangePicker::make('created_at')->label('التاريخ (من - الي)'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    // Check if $data is an array with the expected structure
                    if (is_array($data) && isset($data['created_at'])) {
                        list($startDate, $endDate) = explode(' - ', $data['created_at']);

                        $carbonStartDate = Carbon::createFromFormat('d/m/Y', $startDate);
                        $carbonEndDate = Carbon::createFromFormat('d/m/Y', $endDate);

                        return $query->whereBetween('created_at', [$carbonStartDate, $carbonEndDate]);
                    }

                    return $query;
                })->columnSpan(5)


            ], layout: FiltersLayout::AboveContent)
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                InfolistsSection::make()
                    ->schema([
                        Split::make([
                            Grid::make(3)
                                ->schema([
                                    Group::make([
                                        TextEntry::make('id')->label('رقم البوليصه'),
                                        TextEntry::make('company.name')->label('اسم شركتك')->badge()->copyable()->icon('heroicon-m-envelope'),
                                        TextEntry::make('customers.name')->label('شركه العميل')->badge(),
                                        TextEntry::make('customers.phone')->label('موبيل العميل')->badge(),
                                        TextEntry::make('status')->label('الحاله')->badge(),
                                    ]),
                                    Group::make([
                                        TextEntry::make('transportation_companies.name')->label('مقاول النقل')->badge(),
                                        TextEntry::make('transportation_companies.phone')->label('موبيل مقاول النقل')->badge(),
                                        TextEntry::make('transport_workers.name')->label('السائق')->badge(),
                                        TextEntry::make('transport_workers.phone')->label('موبيل السائق')->badge(),
                                        TextEntry::make('transport_workers.car_number')->label('رقم العربيه')->badge(),
                                    ]),

                                    Group::make([
                                        TextEntry::make('quarrie.name')->label('المحجر')->badge(),
                                        TextEntry::make('ton')->label('الوزن')->badge(),
                                        TextEntry::make('materials.name')->label('نوع الخامه')->badge(),
                                        TextEntry::make('price_per_ton')->label('سعر الطن من الخامه')->badge(),
                                        TextEntry::make('total_invoice')->label('اجمالي الفاتوره')->badge(),
                                    ]),
                                ]),
                                ImageEntry::make('image')
                                ->defaultImageUrl(asset('images/pexels-pixabay-162639.jpg'))
                                ->hiddenLabel()
                                ->circular()
                                ->grow(false),
                        ])->from('lg'),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSupplies::route('/'),
            'create' => Pages\CreateSupply::route('/create'),
            'edit' => Pages\EditSupply::route('/{record}/edit'),
            'view' => Pages\SupplyView::route('/{record}'),
        ];
    }
}

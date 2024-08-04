<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpensesResource\Pages;
use App\Filament\Resources\ExpensesResource\RelationManagers;
use App\Models\Expenses;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Supply;
use Filament\Forms\Components\Toggle;
use App\Models\User;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Tables\Columns\TextColumn;

use Filament\Tables\Filters\Filter;
use Malzariey\FilamentDaterangepickerFilter\Fields\DateRangePicker;
use Carbon\Carbon;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\ActionGroup;




class ExpensesResource extends Resource
{
    protected static ?string $model = Expenses::class;

    protected static ?string $navigationLabel = 'المصروفات';

    protected static ?string $pluralModelLabel = 'المصروفات';

    protected static ?string $modelLabel = 'مصروف';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('المصروفات')
                ->schema([
                    Select::make('expense_type')->label('نوع المصروف')
                    ->options([
                        'cleanliness' => 'النظافه',
                        'salaries' => 'مرتبات الموظفين',
                        'materialsTransportation' => 'نقل خامات' ,
                        'other' => 'مصاريف اخري'
                    ])
                    ->live()
                    ->required(),

                    DatePicker::make('date')->label('التاريخ')->required(),

                    TextInput::make('workers_number')->label('عدد العمال')
                    ->visible(fn (Get $get): bool => $get('expense_type') == 'cleanliness')->required()->numeric(),
                    TextInput::make('trucks_number')->label('عدد اللوادر')
                    ->visible(fn (Get $get): bool => $get('expense_type') == 'cleanliness')->required()->numeric(),
                    TextInput::make('workers_hours_number')->label('عدد ساعات عمل العمال')
                    ->visible(fn (Get $get): bool => $get('expense_type') == 'cleanliness')->required()->numeric(),
                    TextInput::make('trucks_hours_number')->label('عدد ساعات عمل اللوادر')
                    ->visible(fn (Get $get): bool => $get('expense_type') == 'cleanliness')->required()->numeric(),
                    TextInput::make('workers_hourly_price')->label('سعر ساعه العامل')
                    ->visible(fn (Get $get): bool => $get('expense_type') == 'cleanliness')->required()->numeric(),
                    TextInput::make('trucks_hourly_price')->label('سعر ساعه اللودر')
                    ->visible(fn (Get $get): bool => $get('expense_type') == 'cleanliness')->required()->numeric(),
                    TextInput::make('transportation_expenses')->label('مصاريف انتقال العمال')
                    ->visible(fn (Get $get): bool => $get('expense_type') == 'cleanliness')->required()->numeric(),



                    Select::make('supply_id')
                    ->label('رقم الفاتوره')
                    ->relationship(name: 'supply', titleAttribute: 'reference')
                    ->options(function () {
                        $excludedSupplyIds = Expenses::select('supply_id')->pluck('supply_id')->toArray();
                        $data = Supply::whereIn('status', ['invoiced', 'Collected'])
                        ->whereNotIn('id', $excludedSupplyIds)
                        ->get()
                        ->pluck('reference', 'id');

                        return $data;

                    })
                    ->searchable()
                    ->visible(fn (Get $get): bool => $get('expense_type') == 'materialsTransportation')
                    ->required(),

                    Toggle::make('isSystemuser')
                    ->label('هل هو مستخدم للنظام')
                    ->visible(fn (Get $get): bool => $get('expense_type') == 'salaries')
                    ->inline(false)
                    ->default(true)
                    ->live(),


                    TextInput::make('userName')->label('اسم الموظف')
                    ->visible(fn (Get $get): bool => $get('expense_type') == 'salaries' && $get('isSystemuser') == false)
                    ->required(),


                    Select::make('user_salary')
                    ->label('المستخدم')
                    ->options(User::get()->pluck('name' , 'id'))
                    ->searchable()
                    ->visible(fn (Get $get): bool => $get('expense_type') == 'salaries' && $get('isSystemuser') == true)
                    ->required(),


                    MarkdownEditor::make('description')
                    ->label('وصف المصروف')
                    ->visible(fn (Get $get): bool => $get('expense_type') == 'other')
                    ->required(),


                    TextInput::make('expense')->label('قيمه المصروف')
                    ->visible(fn (Get $get): bool => $get('expense_type') == 'salaries' || $get('expense_type') == 'other')
                    ->required()->numeric(),

                    Toggle::make('isPaymentMade')
                    ->label('هل تم الدفع')
                    ->inline(false)
                    ->default(true),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('expense_type')->label('نوع المصروف')
                ->formatStateUsing(function(string $state){
                    return match($state){
                        'cleanliness' => 'نظافه',
                        'salaries' => 'مرتبات',
                        'materialsTransportation' => 'نقل',
                        'other' => 'اخري',
                    };
                })->badge(),
                TextColumn::make('supply.reference')->label('رقم الفاتوره')->badge(),
                TextColumn::make('date')->label('تاريخ المصروف')->badge(),
                TextColumn::make('user.name')->searchable()->label('الموظف')->badge(),
                TextColumn::make('supply.transportation_companies.name')->searchable()->label('مقاول الشحن')->badge(),
                TextColumn::make('supply.cost_of_transporting_a_ton')->label('سعر نقل الطن')->badge()->toggleable(isToggledHiddenByDefault:true),
                TextColumn::make('supply.ton')->label('عدد الاطنان')->badge()->toggleable(isToggledHiddenByDefault:true),
                TextColumn::make('supply.opening_amount')->label('العهده (تخصم تلقائي من اجمالي المصروف)')->badge(),
                TextColumn::make('total')->default(function(Model $record){
                   return $record->supply->cost_of_transporting_a_ton * $record->supply->ton;
                })->label('السعر شامل العهده')->badge()->toggleable(isToggledHiddenByDefault:true),

                TextColumn::make('expense')->label('اجمالي المصروف')->badge()->summarize([
                    Tables\Columns\Summarizers\Sum::make()->money('EGP'),
                ])->searchable(),

                ToggleColumn::make('isPaymentMade')->label('هل تم الدفع؟'),


            ])
            ->filters([

                SelectFilter::make('expense_type')
                ->label('نوع المصروف')
                ->options([
                    'cleanliness' => 'النظافه',
                    'salaries' => 'مرتبات الموظفين',
                    'materialsTransportation' => 'نقل خامات' ,
                    'other' => 'مصاريف اخري'
                ])->columnSpan(1),

                SelectFilter::make('isPaymentMade')
                ->label('حاله الدفع')
                ->options([
                    true => 'تم الدفع',
                    false => 'لم يتم الدفع',
                ])->columnSpan(1),

                Filter::make('created_at')
                ->form([
                    DateRangePicker::make('created_at')->label('التاريخ (من - الي)'),
                ])
                ->query(function (Builder $query, array $data): Builder {

                    if (is_array($data) && isset($data['created_at'])) {
                        list($startDate, $endDate) = explode(' - ', $data['created_at']);

                        $carbonStartDate = Carbon::createFromFormat('d/m/Y', $startDate);
                        $carbonEndDate = Carbon::createFromFormat('d/m/Y', $endDate);

                        return $query->whereBetween('created_at', [$carbonStartDate, $carbonEndDate]);
                    }

                    return $query;
                })->columnSpan(3)
            ],layout: FiltersLayout::AboveContent)
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
            'index' => Pages\ListExpenses::route('/'),
            'create' => Pages\CreateExpenses::route('/create'),
            'edit' => Pages\EditExpenses::route('/{record}/edit'),
        ];
    }
}

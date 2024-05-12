<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplyOrderResource\Pages;
use App\Filament\Resources\SupplyOrderResource\RelationManagers;
use App\Models\Customers;
use App\Models\SupplyOrder;
use Filament\Forms;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SupplyOrderResource extends Resource
{
    protected static ?string $model = SupplyOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'امر التوريد';

    protected static ?string $pluralModelLabel = 'امر التوريد';

    protected static ?string $modelLabel = 'امر توريد';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Select::make('customers_id')
                    ->label('اسم الشركه')
                    ->options(Customers::all()->pluck('name' , 'id'))
                    ->searchable()
                    ->required()
                    ->columnSpanFull(),

                    TextInput::make('supply_number')->label('رقم امر التوريد')->required()->numeric(),
                    TextInput::make('ton')->label('عدد الاطنان')->required()->numeric(),

                    MarkdownEditor::make('comment')->label('تفاصيل اخري')->columnSpanFull(),


                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')->label('اسم الشركه')->searchable(),
                TextColumn::make('supply_number')->label('رقم امر التوريد')->searchable(),
                TextColumn::make('ton')->label('عدد الاطنان'),
                TextColumn::make('comment')->label('تفاصيل اخري')->wrap()->words(20)->toggleable(isToggledHiddenByDefault:true),
                TextColumn::make('created_at')->label('وقت الانشاء')->dateTime(),
                TextColumn::make('updated_at')->label('وقت التعديل')->dateTime()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListSupplyOrders::route('/'),
            'create' => Pages\CreateSupplyOrder::route('/create'),
            'edit' => Pages\EditSupplyOrder::route('/{record}/edit'),
        ];
    }
}

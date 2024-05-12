<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomersResource\Pages;
use App\Filament\Resources\CustomersResource\RelationManagers;
use App\Models\Customers;
use Filament\Forms;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomersResource extends Resource
{
    protected static ?string $model = Customers::class;

    protected static ?string $navigationLabel = 'العملاء';

    protected static ?string $pluralModelLabel = 'العملاء';

    protected static ?string $modelLabel = 'عميل';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'name',
            'email',
            'phone',
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'الاسم' => $record->name,
            'الايميل' => $record->email,
            'رقم التواصل' => $record->phone,
        ];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return CustomersResource::getUrl('edit', ['record' => $record]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('العملاء')
                ->description('يمكنك اضافه معلومات عملائك من هنا')
                ->schema([
                    TextInput::make('name')->label('الاسم')->required(),
                    TextInput::make('email')->label('الايميل الرسمي')->required(),
                    TextInput::make('phone')->label('رقم التواصل')->required(),
                    // MarkdownEditor::make('address')->label('العنوان')->required()->columnSpan(2)

                    Repeater::make('address')
                    ->label('الفروع او العنوان')
                    ->simple(
                        TextInput::make('address')->required(),
                    )
                    ->cloneable()
                    ->required()

                ])->columns(2),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('اسم العميل')->searchable(),
                TextColumn::make('email')
                ->label('الايميل')
                ->copyable()
                ->copyMessage('تم النسخ بنجاح')
                ->badge()
                ->searchable(),
                TextColumn::make('phone')
                ->label('رقم التواصل')
                ->copyable()
                ->copyMessage('تم النسخ بنجاح')
                ->badge()
                ->searchable(),
                TextColumn::make('address')
                ->badge()
                ->wrap()
                ->copyable()
                ->label('العنوان')
                ->toggleable(isToggledHiddenByDefault:true),
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomers::route('/create'),
            'edit' => Pages\EditCustomers::route('/{record}/edit'),
        ];
    }
}

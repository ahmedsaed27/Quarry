<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompaniesResource\Pages;
use App\Filament\Resources\CompaniesResource\RelationManagers;
use App\Models\Companies;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompaniesResource extends Resource
{
    protected static ?string $model = Companies::class;

    protected static ?string $navigationLabel = 'الشركات';

    protected static ?string $pluralModelLabel = 'الشركات';

    protected static ?string $modelLabel = 'شركه';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'name',
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'الاسم' => $record->name,
            'رقم التواصل' => $record->phone,
            'الايميل' => $record->name,
        ];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return CompaniesResource::getUrl('edit', ['record' => $record]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('الشركات')
                ->description('يمكنك اضافه اسماء الشركات الخاصه بك من هنا')
                ->schema([
                    TextInput::make('name')->label('الاسم')->required(),
                    TextInput::make('email')->label('الميل')
                    ->email()
                    ->unique(table:'companies' , column:'email' ,ignoreRecord: true)
                    ->required(),

                    TextInput::make('phone')
                    ->label('رقم التواصل')
                    ->rules([
                        fn (): Closure => function (string $attribute, $value, Closure $fail) {
                            if (!preg_match('/^01[1205][0-9]{8}$/', $value)) {
                                $fail('The :attribute is invalid.');
                            }
                        },
                    ])
                    ->unique(table:'companies' , column:'phone' , ignoreRecord: true)
                    ->required(),

                    TextInput::make('address')
                    ->label('الفروع او العنوان')
                    ->required(),

                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('اسم الشركه')->searchable(),

                TextColumn::make('phone')->label('رقم التواصل')
                ->copyable()
                ->copyMessage('تم النسخ بنجاح')
                ->badge()
                ->searchable(),

                TextColumn::make('email')->label('الميل')
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
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompanies::route('/create'),
            'edit' => Pages\EditCompanies::route('/{record}/edit'),
        ];
    }
}

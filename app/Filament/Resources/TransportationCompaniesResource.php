<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransportationCompaniesResource\Pages;
use App\Filament\Resources\TransportationCompaniesResource\RelationManagers;
use App\Models\TransportationCompanies;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransportationCompaniesResource extends Resource
{
    protected static ?string $model = TransportationCompanies::class;


    protected static ?string $navigationLabel = 'مقاول الشحن';

    protected static ?string $pluralModelLabel = 'مقاول الشحن';

    protected static ?string $modelLabel = 'مقاول الشحن';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                ->description('هنا بنضيف مقاولين النقل اللي بنشتغل معاهم')
                ->schema([
                    TextInput::make('name')->label('الاسم')->placeholder('السلام')->required(),
                    TextInput::make('phone')->label('رقم التواصل')->placeholder('01123036886')->required(),
                    TextInput::make('transportation_cost')->label('سعر النقل / طن')->placeholder('102')->numeric()->required()
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('الاسم')->searchable(),
                TextColumn::make('phone')
                ->label('رقم التواصل')
                ->copyable()
                ->copyMessage('تم النسخ بنجاح')
                ->badge()
                ->searchable(),
                TextColumn::make('created_at')->dateTime()
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
            'index' => Pages\ListTransportationCompanies::route('/'),
            'create' => Pages\CreateTransportationCompanies::route('/create'),
            'edit' => Pages\EditTransportationCompanies::route('/{record}/edit'),
        ];
    }
}

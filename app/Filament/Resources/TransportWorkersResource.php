<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransportWorkersResource\Pages;
use App\Filament\Resources\TransportWorkersResource\RelationManagers;
use App\Models\TransportationCompanies;
use App\Models\TransportWorkers;
use Filament\Forms;
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

class TransportWorkersResource extends Resource
{
    protected static ?string $model = TransportWorkers::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'السائقين';

    protected static ?string $pluralModelLabel = 'السائقين';

    protected static ?string $modelLabel = 'سائق';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                ->description('هنا بنضيف عمال النقل اللي بنشتغل معاهم و بنصنفهم تبع انهي مقاول')
                ->schema([
                    Select::make('transportation_companies_id')
                    ->label('المقاول')
                    ->options(TransportationCompanies::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                    TextInput::make('name')->label('الاسم')->required(),

                    TextInput::make('phone')->label('رقم التواصل')->required(),

                    TextInput::make('car_number')->label('رقم العربيه')->required(),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('Company.name')->label('المقاول')->searchable(),
                TextColumn::make('name')->label('السائق')->searchable(),
                TextColumn::make('phone')
                ->label('رقم التواصل')
                ->copyable()
                ->copyMessage('تم النسخ بنجاح')
                ->badge()
                ->searchable(),
                TextColumn::make('created_at')->label('وقت الانشاء')->dateTime()
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
            'index' => Pages\ListTransportWorkers::route('/'),
            'create' => Pages\CreateTransportWorkers::route('/create'),
            'edit' => Pages\EditTransportWorkers::route('/{record}/edit'),
        ];
    }
}

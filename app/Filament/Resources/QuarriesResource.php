<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuarriesResource\Pages;
use App\Filament\Resources\QuarriesResource\RelationManagers;
use App\Filament\Resources\QuarriesResource\RelationManagers\MaterialsRelationManager;
use App\Models\Materials;
use App\Models\Quarries;
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
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section as InfolistsSection;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Model;

class QuarriesResource extends Resource
{
    protected static ?string $model = Quarries::class;

    protected static ?string $navigationLabel = 'المحاجر';

    protected static ?string $pluralModelLabel = 'المحاجر';

    protected static ?string $modelLabel = 'محجر';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'name',
            'phone',
            'materials.name'
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'الاسم' => $record->name,
            'رقم التواصل' => $record->phone,
            'العنوان' => implode(', ', $record->address),
        ];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return QuarriesResource::getUrl('view', ['record' => $record]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('المحاجر')
                ->description('يمكنك اضافه اسماء المحاجر الخاصه بك من هنا')
                ->schema([
                    TextInput::make('name')->label('الاسم')->required(),
                    TextInput::make('phone')->label('رقم التواصل')->required(),

                    Repeater::make('address')
                    ->label('العنوان')
                    ->simple(
                        TextInput::make('address')->required(),
                    )->required()
                    ->columnSpanFull()
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('المحجر')->searchable(),
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
                ->badge()
                ->label('العنوان'),
                TextColumn::make('created_at')->label('وقت الانشاء')->dateTime(),
                TextColumn::make('updated_at')->label('وقت التعديل')->dateTime()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
                                        TextEntry::make('name')->label('اسم المحجر')->badge()->copyable()->icon('heroicon-m-envelope'),
                                    ]),
                                    Group::make([
                                        TextEntry::make('phone')->label('رقم المحجر')->badge()->copyable()->icon('heroicon-m-envelope'),

                                    ]),

                                    Group::make([
                                        TextEntry::make('address')->label('العنوان')->copyable()->badge(),

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
            MaterialsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuarries::route('/'),
            'create' => Pages\CreateQuarries::route('/create'),
            'edit' => Pages\EditQuarries::route('/{record}/edit'),
            'view' => Pages\ViewQuarries::route('/{record}'),
        ];
    }
}

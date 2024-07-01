<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
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


class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    protected static ?string $navigationLabel = 'المستخدمين';

    protected static ?string $pluralModelLabel = 'المستخدمين';

    protected static ?string $modelLabel = 'مستخدم';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                ->schema([
                    TextInput::make('name')->label('الاسم')->required(),
                    TextInput::make('email')->label('الايميل')->unique(table:'users' , column:'email' ,ignoreRecord:true)->email()->required(),
                    TextInput::make('password')->label('الرمز السري')->password()->confirmed()->dehydrated(fn ($state) => filled($state))->required(fn (string $context): bool => $context === 'create'),
                    TextInput::make('password_confirmation')->label('تاكيد الرمز السري')->password()->dehydrated(fn ($state) => filled($state))->required(fn (string $context): bool => $context === 'create'),
                    Select::make('roles')
                    ->label('الادوار')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('الاسم')->searchable(),
                TextColumn::make('email')->label('الايميل')->searchable()->badge()->copyable(),
                TextColumn::make('roleNames')
                ->label('الادوار')
                ->searchable()
                ->badge()
                ->separator(','),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\QuarriesResource\RelationManagers;

use App\Models\Materials;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MaterialsRelationManager extends RelationManager
{
    protected static string $relationship = 'materials';

    protected static bool $isLazy = false;

    protected static ?string $title = 'الخامات';
    protected static ?string $modelLabel = 'الخامات';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('اسم الخامه')->required(),
                TextInput::make('price')->label('السعر')->required()->numeric()->rules([
                    fn (): Closure => function (string $attribute, $value, Closure $fail) {
                        if ($value == 0) {
                            $fail(':attribute يجب ان يكون اكبر من 0');
                        }
                    },
                ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('اسم الخامه'),
                Tables\Columns\TextColumn::make('price')->label('السعر'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                ->form(fn (AttachAction $action): array => [
                    $action->getRecordSelect(),
                    TextInput::make('price')->label('السعر')->required()->numeric()->rules([
                        fn (): Closure => function (string $attribute, $value, Closure $fail) {
                            if ($value == 0) {
                                $fail(':attribute يجب ان يكون اكبر من 0');
                            }
                        },
                    ]),
                ])->label('ارفاق خامه للمحجر'),
                Tables\Actions\CreateAction::make()
                ->label('انشاء خامه و ارفاقها الي المحجر'),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}

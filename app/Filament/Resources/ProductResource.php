<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 3;

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name . ' ('. $record->product_id .')';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'product_id'];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('product_id')
                            ->label('Product ID')
                            ->required()
                            ->maxLength(255)
                            ->unique(column: 'product_id', ignoreRecord: true)
                            ->helperText('A unique product identifier, for ex SKU-1234'),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Select::make('type')
                            ->options([
                                1 => 'Service',
                                2 => 'Physical'
                            ])
                            ->required()
                            ->helperText('Type of product'),
                        TextInput::make('price')
                                ->required()
                                ->mask(fn (TextInput\Mask $mask) => $mask->money()),
                        Toggle::make('is_available')
                                ->label('Is available for purchase?')
                                ->inline()
                    ])
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product_id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('price')
                    ->money(shouldConvert: true)
                    ->sortable(),
                BadgeColumn::make('type')
                    ->enum([
                        1 => 'Service',
                        2 => 'Physical'
                    ])
                    ->colors([
                        'secondary' => 1,
                        'warning' => 2
                    ])
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        1 => 'Service',
                        2 => 'Physical'
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->action(function(){
                        Notification::make()
                            ->title('Now, now, don\'t be cheeky, leave some records for others to play with!')
                            ->warning()
                            ->send();
                    }),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }    
}

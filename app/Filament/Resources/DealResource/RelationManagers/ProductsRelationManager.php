<?php

namespace App\Filament\Resources\DealResource\RelationManagers;

use App\Models\DealProduct;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('product_id')
                    ->label('Product')
                    ->options(Product::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function($state, callable $set){
                        $price = Product::where('id', $state)
                            ->pluck('price')
                            ->first();
                            // dd($product);
                        $set('price_per_unit', (string)($price));
                    }),
                TextInput::make('quantity')
                    ->numeric()
                    ->required()
                    ->default(1)
                    ->reactive()
                    ->afterStateUpdated(fn($state, callable $set, callable $get) => 
                        $set('total_amount', (string)($get('price_per_unit') * $state))),
                TextInput::make('price_per_unit')
                    ->mask(fn (TextInput\Mask $mask) => $mask->money())
                    ->numeric()
                    ->required()
                    ->helperText('Price per unit of product')
                    ->reactive()
                    ->afterStateUpdated(fn($state, callable $set, callable $get) => 
                        $set('total_amount', (string)($get('quantity') * $state))),
                TextInput::make('total_amount')
                    ->label('Total amount')
                    ->mask(fn (TextInput\Mask $mask) => $mask->money())
                    ->disabled()
                    ->default(0)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.product_id')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('price_per_unit')
                    ->money(shouldConvert: true)
                    ->sortable(),
                TextColumn::make('quantity')
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->money(shouldConvert: true)
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add product'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label('Remove')
                    ->modalHeading('Remove product'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->label('Remove all')
                    ->modalHeading('Remove all products'),
            ]);
    }    
}

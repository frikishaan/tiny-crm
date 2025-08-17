<?php

namespace App\Filament\Resources\DealResource\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\RawJs;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->numeric()
                    ->required()
                    ->helperText('Price per unit of product')
                    ->reactive()
                    ->afterStateUpdated(fn($state, callable $set, callable $get) => 
                        $set('total_amount', (string)($get('quantity') * $state))),
                TextInput::make('total_amount')
                    ->label('Total amount')
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->numeric()
                    ->disabled()
                    ->default(0)
            ]);
    }

    public function table(Table $table): Table
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
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('quantity')
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->money('USD')
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add product'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->label('Remove')
                    ->modalHeading('Remove product'),
            ])
            ->toolbarActions([
                DeleteBulkAction::make()
                    ->label('Remove all')
                    ->modalHeading('Remove all products'),
            ]);
    }    
}

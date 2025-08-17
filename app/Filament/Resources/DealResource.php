<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\DealResource\Pages\ListDeals;
use App\Filament\Resources\DealResource\Pages\CreateDeal;
use App\Filament\Resources\DealResource\Pages\EditDeal;
use App\Filament\Resources\DealResource\Pages;
use App\Filament\Resources\DealResource\RelationManagers\ProductsRelationManager;
use App\Models\Account;
use App\Models\Deal;
use App\Models\Lead;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DealResource extends Resource
{
    protected static ?string $model = Deal::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static string | \UnitEnum | null $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->disabled(fn(?Deal $record) => in_array($record?->status, [2, 3])),
                        Select::make('customer_id')
                            ->label('Customer')
                            ->options(Account::all()->pluck('name', 'id'))
                            ->searchable()
                            ->disabled(fn(?Deal $record) => in_array($record?->status, [2, 3]))
                            ->required(),
                        Select::make('lead_id')
                            ->label('Originating lead')
                            ->options(Lead::all()->pluck('title', 'id'))
                            ->searchable()
                            ->disabled(fn(?Deal $record) => in_array($record?->status, [2, 3])),
                        RichEditor::make('description')
                            ->toolbarButtons([
                                'bold', 'italic', 'underline', 'strike', 'link', 'table', 'undo', 'redo'
                            ])
                    ])
                    ->columnSpan(2),
                
                    Section::make()
                        ->schema([
                            Select::make('status')
                                ->options([
                                    1 => 'Open',
                                    2 => 'Won',
                                    3 => 'Lost'
                                ])
                                ->visible(fn(?Deal $record) => $record != null)
                                ->disabled(),
                            TextInput::make('estimated_revenue')
                                ->label('Estimated revenue')
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
                                ->numeric()
                                ->disabled(fn(?Deal $record) => in_array($record?->status, [2, 3])),
                            TextInput::make('actual_revenue')
                                ->label('Actual revenue')
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
                                ->numeric()
                                ->disabled(fn(?Deal $record) => in_array($record?->status, [2, 3]))
                        ])
                        ->columnSpan(1)
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer.name')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make()
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
            ProductsRelationManager::class
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => ListDeals::route('/'),
            'create' => CreateDeal::route('/create'),
            'edit' => EditDeal::route('/{record}/edit'),
        ];
    }    
}

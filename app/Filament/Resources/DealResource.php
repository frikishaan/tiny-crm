<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DealResource\Pages;
use App\Filament\Resources\DealResource\RelationManagers\ProductsRelationManager;
use App\Models\Account;
use App\Models\Deal;
use App\Models\Lead;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Form;
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

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                            ->disableToolbarButtons([
                                'attachFiles',
                                'codeBlock'
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
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        1 => 'Open',
                        2 => 'Won',
                        3 => 'Lost'
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
            ProductsRelationManager::class
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDeals::route('/'),
            'create' => Pages\CreateDeal::route('/create'),
            'edit' => Pages\EditDeal::route('/{record}/edit'),
        ];
    }    
}

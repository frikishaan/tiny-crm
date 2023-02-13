<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountResource\Pages;
use App\Filament\Resources\AccountResource\RelationManagers;
use App\Filament\Resources\AccountResource\RelationManagers\ContactsRelationManager;
use App\Filament\Resources\AccountResource\RelationManagers\LeadsRelationManager;
use App\Models\Account;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccountResource extends Resource
{
    protected static ?string $model = Account::class;

    protected static ?string $navigationIcon = 'heroicon-o-office-building';

    protected static ?string $navigationGroup = 'People';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Card::make([
                            TextInput::make('name')
                                ->required(),
                            TextInput::make('email')
                                ->email(),
                            TextInput::make('phone')
                                ->tel(),
                            TextInput::make('address')
                        ])
                    ])
                    ->columnSpan(['lg' => 2]),
                Card::make()
                    ->schema([
                        TextInput::make('total_sales')
                            ->mask(fn (TextInput\Mask $mask) => $mask
                                ->numeric()
                                ->decimalPlaces(2)
                                ->decimalSeparator('.')
                                ->thousandsSeparator(',')
                                ->normalizeZeros()
                                ->padFractionalZeros()
                            )
                            ->prefix('$')
                            ->disabled()
                    ])
                    ->columnSpan(['lg' => 1])
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('total_sales')
                    ->money(shouldConvert: true)
            ])
            ->filters([
                //
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
            ContactsRelationManager::class,
            LeadsRelationManager::class
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccounts::route('/'),
            'create' => Pages\CreateAccount::route('/create'),
            'edit' => Pages\EditAccount::route('/{record}/edit'),
        ];
    }    
}

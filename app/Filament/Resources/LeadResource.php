<?php

namespace App\Filament\Resources;

use App\Enums\LeadStatus;
use App\Filament\Resources\LeadResource\Pages;
use App\Filament\Resources\LeadResource\RelationManagers;
use App\Models\Account;
use App\Models\Lead;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static ?string $navigationIcon = 'heroicon-o-phone-incoming';

    protected static ?string $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 1;

    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 1)->count();
    }

    protected static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 10 ? 'warning' : 'primary';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('title')
                            ->required(),
                        Select::make('customer_id')
                            ->label('Customer')
                            ->options(Account::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                    ])
                    ->columnSpan(2),
                Card::make()
                    ->schema([
                        Select::make('status')
                            ->options([
                                1 => 'Prospect',
                                2 => 'Open',
                                3 => 'Qualified',
                                4 => 'Disqualified'
                            ])
                            ->required(),
                        TextInput::make('estimated_revenue')
                            ->label('Estimated revenue')
                            ->mask(fn (TextInput\Mask $mask) => $mask->money())
                    ])
                    ->columnSpan(1)
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
                BadgeColumn::make('status')
                    ->enum([
                        1 => 'Prospect',
                        2 => 'Open',
                        3 => 'Qualified',
                        4 => 'Disqualified'
                    ])
                    ->colors([
                        'secondary' => 1,
                        'warning' => 2,
                        'success' => 3,
                        'danger' => 4
                    ])
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        1 => 'Prospect',
                        2 => 'Open',
                        3 => 'Qualified',
                        4 => 'Disqualified'
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListLeads::route('/'),
            'create' => Pages\CreateLead::route('/create'),
            'edit' => Pages\EditLead::route('/{record}/edit'),
        ];
    }    
}

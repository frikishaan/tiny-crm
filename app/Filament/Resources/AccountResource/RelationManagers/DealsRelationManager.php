<?php

namespace App\Filament\Resources\AccountResource\RelationManagers;

use App\Filament\Resources\DealResource;
use App\Models\Deal;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DealsRelationManager extends RelationManager
{
    protected static string $relationship = 'deals';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('actual_revenue')
                    ->sortable()
                    ->money(shouldConvert: true),
                BadgeColumn::make('status')
                    ->enum([
                        1 => 'Open',
                        2 => 'Won',
                        3 => 'Lost'
                    ])
                    ->colors([
                        'secondary' => 1,
                        'success' => 2,
                        'danger' => 3
                    ])
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        1 => 'Open',
                        2 => 'Won',
                        3 => 'Lost'
                    ])
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->url(DealResource::getUrl('create')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn(Deal $record) => DealResource::getUrl('edit', ['record' => $record->id])),
                Tables\Actions\DeleteAction::make(),
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
}

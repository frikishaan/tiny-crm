<?php

namespace App\Filament\Resources\AccountResource\RelationManagers;

use App\Filament\Resources\LeadResource;
use App\Models\Lead;
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

class LeadsRelationManager extends RelationManager
{
    protected static string $relationship = 'leads';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                TextInput::make('estimated_revenue')
                    ->label('Estimated revenue')
                    ->mask(fn (TextInput\Mask $mask) => $mask->money())
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('estimated_revenue')
                    ->sortable()
                    ->money(shouldConvert: true),
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
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil-alt')
                    ->url(fn(Lead $record) => LeadResource::getUrl('edit', ['record' => $record->id])),
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

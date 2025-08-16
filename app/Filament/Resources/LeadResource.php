<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\LeadResource\Pages\ListLeads;
use App\Filament\Resources\LeadResource\Pages\CreateLead;
use App\Filament\Resources\LeadResource\Pages\EditLead;
use App\Filament\Resources\LeadResource\Pages;
use App\Filament\Resources\LeadResource\RelationManagers;
use App\Models\Account;
use App\Models\Lead;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-phone-arrow-down-left';

    protected static string | \UnitEnum | null $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 1)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('status', 1)->count() > 10 ? 'warning' : 'primary';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('title')
                            ->maxLength(255)
                            ->required()
                            ->disabled(fn(?Lead $record) => in_array($record?->status, [3, 4])),
                        Select::make('customer_id')
                            ->label('Customer')
                            ->relationship('customer', 'name')
                            ->options(Account::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->createOptionForm([
                                TextInput::make('name')
                                ->required(),
                                TextInput::make('email')
                                    ->email()
                                    ->unique()
                            ])
                            ->createOptionAction(function (Action $action) {
                                return $action
                                    ->modalHeading('Create customer')
                                    ->modalSubmitAction('Create customer')
                                    ->modalWidth('lg');
                            })
                            ->disabled(fn(?Lead $record) => in_array($record?->status, [3, 4])),
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
                                1 => 'Prospect',
                                2 => 'Open',
                                3 => 'Qualified',
                                4 => 'Disqualified'
                            ])
                            ->required()
                            ->visible(fn(?Lead $record) => $record != null)
                            ->disabled(fn(?Lead $record) => in_array($record?->status, [3, 4])),
                        Select::make('source')
                            ->options([
                                1 => 'Email',
                                2 => 'Event',
                                3 => 'Phone',
                                4 => 'Referral',
                                5 => 'Web'
                            ]),
                        DatePicker::make('created_at')
                            ->format('d/m/Y')
                            ->visible(fn(?Lead $record) => $record != null)
                            ->disabled(),
                        DatePicker::make('date_qualified')
                            ->format('d/m/Y')
                            ->disabled()
                            ->visible(fn(?Lead $record) => $record?->status == 3),
                        DatePicker::make('date_disqualified')
                            ->format('d/m/Y')
                            ->disabled()
                            ->visible(fn(?Lead $record) => $record?->status == 4),
                        Select::make('disqualification_reason')
                            ->label('Reason for disqualification')
                            ->options([
                                1 => 'Budget',
                                2 => 'Bad/fake data',
                                3 => 'Not responsive',
                                4 => 'Lost to Competitor',
                                5 => 'Timeline'
                            ])
                            ->disabled()
                            ->visible(fn(?Lead $record) => $record?->status == 4),
                        TextInput::make('estimated_revenue')
                            ->label('Estimated revenue')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->numeric()
                            ->disabled(fn(?Lead $record) => in_array($record?->status, [3, 4]))
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
                    ->searchable()
                    ->sortable(),
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
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => ListLeads::route('/'),
            'create' => CreateLead::route('/create'),
            'edit' => EditLead::route('/{record}/edit'),
        ];
    }

    private function qualified(Lead $record): bool
    {
        return $record->status == 3;
    }
}

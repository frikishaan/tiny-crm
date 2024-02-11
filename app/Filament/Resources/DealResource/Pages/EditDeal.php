<?php

namespace App\Filament\Resources\DealResource\Pages;

use App\Enums\DealStatus;
use App\Filament\Resources\DealResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditDeal extends EditRecord
{
    protected static string $resource = DealResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('close_as_won')
                ->label('Close As Won')
                ->icon('heroicon-o-check-badge')
                ->action(function() {
                    $this->record->closeAsWon();
                    $this->refreshFormData(['status']);
                    Notification::make()
                        ->title('Deal has been closed as won')
                        ->success();
                })
                ->visible(!in_array($this->record->status, [DealStatus::Won, DealStatus::Lost])),
            Action::make('close_as_lost')
                ->label('Close As Lost')
                ->icon('heroicon-o-x-circle')
                ->color('warning')
                ->action(function() {
                    $this->record->closeAsLost();
                    $this->refreshFormData(['status']);
                    Notification::make()
                        ->title('Deal has been closed as lost')
                        ->success();
                })
                ->visible(!in_array($this->record->status, [DealStatus::Won, DealStatus::Lost])),
            DeleteAction::make()
                ->visible(!in_array($this->record->status, [DealStatus::Won, DealStatus::Lost])),
        ];
    }
}

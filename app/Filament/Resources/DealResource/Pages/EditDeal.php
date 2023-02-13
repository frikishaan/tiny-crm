<?php

namespace App\Filament\Resources\DealResource\Pages;

use App\Filament\Resources\DealResource;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDeal extends EditRecord
{
    protected static string $resource = DealResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('close_as_won')
                ->label('Close As Won')
                ->icon('heroicon-o-badge-check')
                ->action('closeAsWon')
                ->visible(!in_array($this->record->status, [2, 3])),
            Action::make('close_as_lost')
                ->label('Close As Lost')
                ->icon('heroicon-o-x-circle')
                ->color('warning')
                ->action('closeAsLost')
                ->visible(!in_array($this->record->status, [2, 3])),
            DeleteAction::make()
                ->visible(!in_array($this->record->status, [2, 3])),
        ];
    }

    public function closeAsWon()
    {
        $this->record->closeAsWon();
        $this->refreshFormData(['status']);
        $this->notify('success', 'Deal has been closed as won');
    }
    
    public function closeAsLost()
    {
        $this->record->closeAsLost();
        $this->refreshFormData(['status']);
        $this->notify('success', 'Deal has been closed as lost');
    }
}

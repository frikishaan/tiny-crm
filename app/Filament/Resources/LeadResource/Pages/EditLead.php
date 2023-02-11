<?php

namespace App\Filament\Resources\LeadResource\Pages;

use App\Filament\Resources\DealResource;
use App\Filament\Resources\LeadResource;
use App\Models\Deal;
use App\Models\Lead;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLead extends EditRecord
{
    protected static string $resource = LeadResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('qualify')
                ->action('qualifyLead')
                ->icon('heroicon-o-badge-check')
                ->visible(!in_array($this->record->status, [3, 4])),
            Action::make('open-deal')
                ->action('openDeal')
                ->icon('heroicon-o-arrow-circle-right')
                ->visible($this->record->status == 3),
            Actions\DeleteAction::make(),
        ];
    }

    public function qualifyLead()
    {
        // Create Deal
        $deal = Deal::create([
            'lead_id' => $this->record->id,
            'title' => $this->record->title,
            'customer_id' => $this->record->customer_id,
            'estimated_revenue' => $this->record->estimated_revenue
        ]);

        $this->record->status = 3;
        $this->record->date_qualified = now();
        $this->record->update();

        $this->notify('success', 'Lead Qualified');

        $this->redirect(DealResource::getUrl('edit', ['record' => $deal->id]));
    }

    public function openDeal()
    {
        $deal = Deal::where('lead_id', $this->record->id)->first();

        if($deal)
            $this->redirect(DealResource::getUrl('edit', ['record' => $deal->id]));
        else
            $this->notify('danger', 'Deal does not exists');
    }
}

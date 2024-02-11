<?php

namespace App\Filament\Resources\LeadResource\Pages;

use App\Enums\LeadStatus;
use App\Filament\Resources\DealResource;
use App\Filament\Resources\LeadResource;
use App\Models\Deal;
use App\Models\Lead;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLead extends EditRecord
{
    protected static string $resource = LeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('qualify')
                ->action(function() {
                    $deal = $this->record->qualify();

                    Notification::make()
                        ->title('Lead Qualified')
                        ->success();

                    $this->redirect(DealResource::getUrl('edit', ['record' => $deal->id]));
                })
                ->icon('heroicon-o-check-badge')
                ->visible(!in_array($this->record->status, [LeadStatus::Qualified, LeadStatus::Disqualified])),
            Action::make('disqualify')
                ->icon('heroicon-o-x-circle')
                ->color('warning')
                ->form([
                    Select::make('disqualification_reason')
                        ->label('Reason for disqualification')
                        ->options([
                            1 => 'Budget',
                            2 => 'Bad/fake data',
                            3 => 'Not responsive',
                            4 => 'Lost to Competitor',
                            5 => 'Timeline'
                        ]),
                    Textarea::make('disqualification_description')
                        ->label('Description')
                ])
                ->action(function (array $data, Lead $record): void {
                    $record->disqualify(
                        $data['disqualification_reason'], 
                        $data['disqualification_description']
                    );
            
                    Notification::make()
                        ->title('Lead has been disqualified')
                        ->success();
                })
                ->modalHeading('Disqualify lead')
                ->visible(!in_array($this->record->status, [LeadStatus::Qualified, LeadStatus::Disqualified])),
            Action::make('open-deal')
                ->action('openDeal')
                ->icon('heroicon-o-arrow-right-circle')
                ->visible(in_array($this->record->status, [LeadStatus::Qualified])),
            DeleteAction::make()
                ->visible(!in_array($this->record->status, [LeadStatus::Qualified, LeadStatus::Disqualified])),
        ];
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

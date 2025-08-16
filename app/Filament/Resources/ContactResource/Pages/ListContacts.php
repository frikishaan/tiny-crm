<?php

namespace App\Filament\Resources\ContactResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\ContactResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContacts extends ListRecords
{
    protected static string $resource = ContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('change_password')
                ->action(function(array $data) {
                    if($this->record->id != 1){
                        $this->record->password = Hash::make($data['password']);
                        $this->record->save();
                    }
            
                    Notification::make()
                        ->title('Password updated successfully')
                        ->success();
                })
                ->form([
                    TextInput::make('password')
                        ->label('New password')
                        ->password()
                        ->confirmed(),
                    TextInput::make('password_confirmation')
                        ->label('Confirm new password')
                        ->password()
                ]),
            // Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        /*
        * Remove this logic in production
        */
        if($record->id != 1){
            $record->update($data);
        }
    
        return $record;
    }
}

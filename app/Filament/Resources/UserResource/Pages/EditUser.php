<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('change_password')
                ->action('updatePassword')
                ->form([
                    TextInput::make('password')
                        ->label('New password')
                        ->password()
                        ->confirmed(),
                    TextInput::make('password_confirmation')
                        ->label('Confirm new password')
                        ->password()
                ]),
            Actions\DeleteAction::make(),
        ];
    }

    public function updatePassword(array $data): void
    {
        $this->record->password = Hash::make($data['password']);
        $this->record->save();

        $this->notify('success', 'Password updated successfully');
    }
}

<?php

namespace App\Filament\Pages\Auth;

use App\Models\Role;
use App\Models\User;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Register as BaseRegister;

class Register extends BaseRegister
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        TextInput::make('phone_num')
                            ->label(__('attributes.phone_number'))
                            ->tel()
                            ->unique(User::class, 'phone_num', ignoreRecord: true)
                            ->maxLength(255)
                            ->default(null),
                        Hidden::make('role_id')->default(Role::firstOrCreate(['name' => 'user'])->id),
                    ])
                    ->statePath('data'),
            ),
        ];
    }
}

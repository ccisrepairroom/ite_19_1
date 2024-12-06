<?php

namespace App\Filament\Auth;

use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Pages\Auth\Register as AuthRegister;
use Filament\Forms\Components\FileUpload;
use Spatie\Permission\Models\Role;
use App\Models\User;

class Register extends AuthRegister
{
    public function form(Form $form): Form
    {
        return $form->schema([
            FileUpload::make('profile_image')
                ->avatar(),
            
            // Use Filament's existing form components
            $this->getNameFormComponent(),
            $this->getEmailFormComponent(),
            $this->getPasswordFormComponent(),
            $this->getPasswordConfirmationFormComponent(),

            // Add custom fields
            TextInput::make('contact_number')
                ->label('Contact Number')
                ->required()
                ->maxLength(15),

            Select::make('is_frequent_shopper')
                ->label('Is Frequent Shopper?')
                ->options([
                    1 => 'Yes',
                    0 => 'No',
                ])
                ->default(0) // Default to "No"
                ->required(),

            Select::make('roles')
                ->label('Roles')
                ->multiple()
                ->options(Role::pluck('name', 'id'))
                ->preload()
                ->searchable(),
        ])
        ->statePath('data');
    }

    protected function onSubmit(array $data): void
    {
        // Create the user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'contact_number' => $data['contact_number'],
            'is_frequent_shopper' => $data['is_frequent_shopper'],
        ]);

        // Assign roles to the user using assignRole
        if (isset($data['roles']) && is_array($data['roles'])) {
            $user->assignRole($data['roles']); // Automatically assigns the roles
        }

        // Log in the user
        auth()->login($user);
    }
}

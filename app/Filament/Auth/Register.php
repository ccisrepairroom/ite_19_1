<?php

namespace App\Filament\Auth;

use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Pages\Auth\Register as AuthRegister;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Spatie\Permission\Models\Role; // Import Role class

class Register extends AuthRegister
{
    protected function createUser(array $data): User
    {
        // Create the user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'contact_number' => $data['contact_number'],
            'is_frequent_shopper' => $data['is_frequent_shopper'],
        ]);

        // Assign roles if provided
        if (isset($data['roles']) && is_array($data['roles'])) {
            $roleNames = Role::whereIn('id', $data['roles'])->pluck('name'); // Fetch role names by IDs
            $user->assignRole($roleNames);
        }

        return $user;
    }

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
                ->default(0) // Changed to default to 0 for 'No'
                ->required(),

            // Add a role selection field
            Select::make('roles')
                ->label('Roles')
                ->multiple()
                ->options(Role::all()->pluck('name', 'id')) // Get all roles for selection
                ->required(),
        ])
        ->statePath('data');
    }
}

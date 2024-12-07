<?php

namespace App\Filament\Auth;

use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Pages\Auth\Register as AuthRegister;
use Filament\Forms\Components\FileUpload;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\FrequentShopper;
use Filament\Forms\Components\DatePicker;


class Register extends AuthRegister
{
    public function form(Form $form): Form
    {
        return $form->schema([
            FileUpload::make('profile_image')
                ->avatar(),
            
            // Use Filament's existing form components
            $this->getNameFormComponent()
            ->required(),
            $this->getEmailFormComponent()
            ->required(),
            $this->getPasswordFormComponent()
            ->required(),
            $this->getPasswordConfirmationFormComponent()
            ->required(),

            // Add custom fields
            TextInput::make('contact_number')
                ->label('Contact Number')
                ->required()
                ->maxLength(15),

            Select::make('is_frequent_shopper')
                ->label('Apply Frequent Shopper Program?')
                ->reactive()
                ->options([
                    1 => 'Yes',
                    0 => 'No',
                ])
                ->default(0) // Default to "No"
                ->required(),
            TextInput::make('frequent_shopper.username')
                ->label('Frequent Shopper Username')
                ->required()
                ->visible(fn(callable $get) => $get('is_frequent_shopper') === 1),
                //->visible(fn($get) => $get('is_frequent_shopper') == 1), // Conditional visibility
            DatePicker::make('frequent_shopper.membership_date')
                ->label('Membership Date')
                ->date()
                ->visible(fn($get) => $get('user.is_frequent_shopper') == 1), // Conditional visibility

            Select::make('role_id')
                ->label('Roles')
                ->options(function () {
                    // Only return vendor (role_id = 3) and customer (role_id = 4)
                    return Role::whereIn('id', [3, 4])->pluck('name', 'id');
                })
                ->afterStateUpdated(function ($state) {
                    // Automatically set the role_id to 3 (vendor) or 4 (customer) based on the selection
                    if ($state == 3) {
                        return 3;  // Set role_id to 3 for vendor
                    } elseif ($state == 4) {
                        return 4;  // Set role_id to 4 for customer
                    }
                })
                ->preload()
                ->required()
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
            'role_id' => $data['role_id'],

        ]);
         // Create Frequent Shopper data if "Yes" was selected
         if ($data['is_frequent_shopper'] == 1) {
            \App\Models\FrequentShopper::create([
                'username' => $data['frequent_shopper_username'],
                'total_spent' => $data['frequent_shopper_total_spent'],
                'point_balance' => $data['frequent_shopper_point_balance'],
                'membership_date' => $data['frequent_shopper_membership_date'],
                'status' => 1, // Active status for frequent shoppers
                'user_id' => $user->id, // Link the frequent shopper data to the user
            ]);
        }

        // Assign roles to the user using assignRole
        if (isset($data['role_id'])) {
            $user->assignRole($data['role_id']); // Automatically assigns the role based on role_id
        }

        // Log in the user
        auth()->login($user);
    }
}

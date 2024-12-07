<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Spatie\Permission\Models\Role; 
use App\Models\User;
use Filament\Resources\Components\Tab;
use Filament\Actions\Action;
use App\Imports\UserImport;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        $user = auth()->user(); // Retrieve the currently authenticated user
        $isVendor = $user && $user->hasRole('vendor');
        $isCustomer = $user && $user->hasRole('customer');

        $actions = [
            Actions\CreateAction::make()
            ->label('Create'),
        ];
   

        return $actions;
    

    }

    public function getTabs(): array
    {
        // Fetch all roles
        $roles = Role::all();
        
        // Create an array of tabs
        $tabs = [];
        
        // Add an "All" tab to show all users
        $tabs[] = Tab::make('All')
            ->badge(User::count())
            ->modifyQueryUsing(fn($query) => $query);

            foreach ($roles as $role) {
                $tabs[] = Tab::make($this->formatLabel($role->name)) // Use the role name for the tab label
                    ->badge(User::where('role_id', $role->id)->count()) // Count users based on role_id
                    ->modifyQueryUsing(fn($query) => $query->where('role_id', $role->id)); // Filter query by role_id
            }

        return $tabs;
    }

    // Helper function to format the role name
    protected function formatLabel(string $label): string
    {
        return ucwords(str_replace('_', ' ', $label)); // Replace underscores with spaces and capitalize
    }
    public function getBreadcrumbs(): array
    {
        return [];
    }
}

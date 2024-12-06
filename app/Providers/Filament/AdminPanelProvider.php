<?php

namespace App\Providers\Filament;

//use Pboivin\FilamentPeek\Tables\Actions\ListPreviewAction;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Widgets\UserStatsWidget;
use App\Filament\Widgets\EquipmentStatusChart;
use App\Filament\Widgets\EquipmentsPerCategory;
use App\Filament\Widgets\EquipmentsPerFacility;
use App\Filament\Widgets\FacilityPerFacilityType;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Auth\Register;




class AdminPanelProvider extends PanelProvider
{

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('puregold')
            ->login()
            ->favicon(asset('images/puregold_logo.png'))
            ->sidebarFullyCollapsibleOnDesktop()
            ->profile()
            ->registration(Register::class)
            ->brandLogo(asset('images/puregold_logo.png'))
            ->brandLogoHeight('4rem')
            ->breadcrumbs(false)
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->colors([
                'primary' => Color::Green,
            ])

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
               
            ])
                //Pages\Dashboard::class,
            //])
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
                //->setNavigationGroup('User Management'),
                //FilamentEditProfilePlugin::make(),
                //FilamentPeekPlugin::make()
                // \EightyNine\Approvals\ApprovalPlugin::make(),

            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([

                //\App\Filament\Widgets\TotalUserWidget::class,
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,

                //App\Filament\Widgets\EquipmentPerFacility::class,
                //Widgets\EquipmentPerCategory::class,
                //UserStatsWidget::class,
                //BorrowStatsWidget::class,
                //EquipmentsPerCategory::class,

                //EquipmentStatusChart::class,
                //FacilityPerFacilityType::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}

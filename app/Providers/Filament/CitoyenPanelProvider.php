<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class CitoyenPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('citoyen')
            ->path('citoyen')
            ->login()
            ->colors([
                'primary' => Color::Emerald,
                'gray'    => Color::Slate,
                'info'    => Color::Cyan,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
                'danger'  => Color::Rose,
            ])
            ->brandName('SignalApp Citoyen')
            ->darkMode(true)
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth('full')
            ->homeUrl(fn () => route('citoyen.dashboard'))
            ->navigationGroups([
                NavigationGroup::make('Tableau de bord')->icon('heroicon-o-home'),
                NavigationGroup::make('Mes signalements')->icon('heroicon-o-exclamation-triangle'),
                NavigationGroup::make('Profil')->icon('heroicon-o-user'),
            ])
            ->discoverResources(
                in: app_path('Filament/Citoyen/Resources'),
                for: 'App\\Filament\\Citoyen\\Resources'
            )
            ->discoverPages(
                in: app_path('Filament/Citoyen/Pages'),
                for: 'App\\Filament\\Citoyen\\Pages'
            )
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([Authenticate::class]);
    }
}

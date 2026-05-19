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

class AgentPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('agent')
            ->path('agent')
            ->login()
            ->colors([
                'primary' => Color::Orange,
                'gray'    => Color::Slate,
                'info'    => Color::Cyan,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
                'danger'  => Color::Rose,
            ])
            ->brandName('SignalApp Agent')
            ->darkMode(true)
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth('screen-2xl')
            ->homeUrl(fn () => route('agent.dashboard'))
            ->navigationGroups([
                NavigationGroup::make('Tableau de bord')->icon('heroicon-o-home'),
                NavigationGroup::make('Missions')->icon('heroicon-o-clipboard-document-list'),
                NavigationGroup::make('Outils')->icon('heroicon-o-wrench-screwdriver'),
                NavigationGroup::make('Profil')->icon('heroicon-o-user'),
            ])
            ->discoverResources(
                in: app_path('Filament/Agent/Resources'),
                for: 'App\\Filament\\Agent\\Resources'
            )
            ->discoverPages(
                in: app_path('Filament/Agent/Pages'),
                for: 'App\\Filament\\Agent\\Pages'
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

<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\LatestEntries;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
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
use pxlrbt\FilamentEnvironmentIndicator\EnvironmentIndicatorPlugin;

class AdminPanelProvider extends PanelProvider
{

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('project')
            ->path('project')
            ->login()
            ->profile()
            ->passwordReset()
            // ->emailVerification()
            ->registration()
            ->renderHook(
                'panels::body.end',
                fn () => view('customFooter'),
            )
            ->colors([
                'primary' => Color::Green,
            ])
            ->plugins([
                EnvironmentIndicatorPlugin::make()
                    ->showBadge(true)
            ])
            ->brandName('Centralized Crime Information System')
            ->favicon(asset('image/logo.jpg'))
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('20rem')

            ->navigationItems([
                NavigationItem::make('USER MANUAL')
                    ->url('', shouldOpenInNewTab: true)
                    ->group('GUIDE')
                    ->sort(3)
                    ->icon('heroicon-m-book-open'),
            ])

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                LatestEntries::class,
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

<?php

namespace App\Providers\Filament;

use App\Filament\Pages\CreateTeam;
use App\Filament\Pages\EditProfile;
use App\Filament\Pages\EditTeam;
use App\Http\Middleware\EnsureHasTeam;
use App\Models\Team;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
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
use Laravel\Jetstream\Features;

class AppPanelProvider extends PanelProvider
{


    public function panel(Panel $panel): Panel
    {
        $panel
            ->id('app')
            ->path('app')
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources')
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\\Filament\\App\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\\Filament\\App\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
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
                EnsureHasTeam::class
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('Profile')
                    ->icon('heroicon-o-user-circle')
                    ->url(fn() => $this->shouldRegisterMenuItem()
                        ? url(EditProfile::getUrl())
                        : url($panel->getPath())),
            ]);

        if (Features::hasTeamFeatures()) {
            $panel
                ->tenant(Team::class)
                ->tenantRegistration(CreateTeam::class)
                ->tenantProfile(EditTeam::class)
                ->userMenuItems([
                    MenuItem::make()
                        ->label('Team Settings')
                        ->icon('heroicon-o-cog-6-tooth')
                        ->url(fn() => $this->shouldRegisterMenuItem()
                            ? url(EditTeam::getUrl())
                            : url($panel->getPath())),
                ]);
        }
        return $panel;
    }

    public function shouldRegisterMenuItem(): bool
    {
        //        $hasVerifiedEmail = auth()->user()?->hasVerifiedEmail();
        return Filament::hasTenancy() && Filament::getTenant();
    }
}

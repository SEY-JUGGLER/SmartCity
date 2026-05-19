<?php

namespace App\Providers;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::addNamespace('View', app_path('View'));

        // Injecter Chart.js dans le panel Filament
        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_END,
            fn(): string => '<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<style>
/* Section headings */
.fi-wi-widget .fi-section-header-heading { display:flex; align-items:center; gap:0.5rem; }
.fi-wi-widget .fi-section-header-heading svg { width:20px !important; height:20px !important; flex-shrink:0; }

/* StatsOverviewWidget cards */
.fi-wi-stats-overview-stat { padding:1rem 1.25rem !important; border:1px solid rgba(0,0,0,0.06) !important; border-radius:0.75rem !important; transition:all 0.15s ease; }
.dark .fi-wi-stats-overview-stat { border-color:rgba(255,255,255,0.07) !important; }
.fi-wi-stats-overview-stat:hover { box-shadow:0 4px 12px rgba(0,0,0,0.06); transform:translateY(-1px); }
.fi-wi-stats-overview-stat-description svg { width:16px !important; height:16px !important; }
.fi-wi-stats-overview-stat-value { font-size:1.5rem !important; font-weight:800 !important; letter-spacing:-0.02em; }
.fi-wi-stats-overview-stat-label { font-size:0.75rem !important; font-weight:500 !important; text-transform:uppercase; letter-spacing:0.05em; }
</style>',
        );

        \Carbon\Carbon::setLocale('fr');
    }
}
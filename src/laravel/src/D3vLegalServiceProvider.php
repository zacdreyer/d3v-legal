<?php
/**
 * Laravel service provider for D3V Legal Notices.
 *
 * Registers the shared renderer as a singleton, merges/publishes the package
 * config, and exposes a Blade component for rendering legal notices.
 */

declare(strict_types=1);

namespace D3vDigital\D3vLegal\Laravel;

use D3vDigital\D3vLegal\D3vLegalRenderer;
use D3vDigital\D3vLegal\Laravel\Blade\LegalNoticeComponent;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class D3vLegalServiceProvider extends ServiceProvider
{
    /**
     * Register the package services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/d3v-legal.php',
            'd3v-legal'
        );

        // The renderer expects the directory that contains legal-libraries/.
        // In this repository layout that is three levels above this file.
        $packageDir = dirname(__DIR__, 3);

        $this->app->singleton(D3vLegalRenderer::class, function () use ($packageDir): D3vLegalRenderer {
            return new D3vLegalRenderer($packageDir);
        });
    }

    /**
     * Bootstrap the package services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/d3v-legal.php' => config_path('d3v-legal.php'),
            ], 'd3v-legal-config');
        }

        Blade::component('d3v-legal-notice', LegalNoticeComponent::class);
    }
}

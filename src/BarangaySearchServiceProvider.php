<?php

namespace YahaayLabs\LaravelBarangaySearch;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use YahaayLabs\LaravelBarangaySearch\Livewire\BarangaySearch;
use YahaayLabs\LaravelBarangaySearch\Services\BarangayService;

class BarangaySearchServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        // Publish configuration
        $this->publishes([
            __DIR__.'/../config/barangay-search.php' => config_path('barangay-search.php'),
        ], 'barangay-search-config');

        // Publish views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/barangay-search'),
        ], 'barangay-search-views');

        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'barangay-search');

        // Register Livewire component
        Livewire::component('barangay-search', BarangaySearch::class);
    }

    /**
     * Register any package services.
     */
    public function register(): void
    {
        // Merge configuration
        $this->mergeConfigFrom(
            __DIR__.'/../config/barangay-search.php',
            'barangay-search'
        );

        // Register the service
        $this->app->singleton(BarangayService::class, function ($app) {
            return new BarangayService(
                config('barangay-search.api_key')
            );
        });
    }
}

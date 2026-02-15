<?php

namespace YahaayLabs\LaravelBarangaySearch\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use YahaayLabs\LaravelBarangaySearch\BarangaySearchServiceProvider;
use Livewire\LivewireServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            LivewireServiceProvider::class,
            BarangaySearchServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('barangay-search.api_key', 'test-key');
    }
}

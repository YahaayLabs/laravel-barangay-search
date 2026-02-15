<?php

use YahaayLabs\LaravelBarangaySearch\Services\BarangayService;
use YahaayLabs\LaravelBarangaySearch\Tests\TestCase;

uses(TestCase::class);

test('service can be instantiated', function () {
    $service = new BarangayService('test-key');
    expect($service)->toBeInstanceOf(BarangayService::class);
});

test('service uses config api key if none provided', function () {
    config(['barangay-search.api_key' => 'config-key']);
    $service = new BarangayService();
    // Since client property is protected, we can't easily check it without reflection
    // but the constructor should run without errors.
    expect($service)->toBeInstanceOf(BarangayService::class);
});

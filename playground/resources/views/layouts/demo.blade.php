<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'laravel-barangay-search' }} — demo</title>
    <link rel="stylesheet" href="{{ asset('css/gis-tokens.css') }}">
    @livewireStyles
</head>
<body>
    <div class="gis-demo">
        <div class="gis-demo-page">
            <header class="gis-demo-header">
                <h1 class="gis-demo-title">laravel-barangay-search</h1>
                <p class="gis-demo-tagline">Philippine barangay autocomplete — Laravel Livewire demo</p>
                <a
                    class="gis-demo-repo"
                    href="https://github.com/YahaayLabs/laravel-barangay-search"
                    target="_blank"
                    rel="noopener noreferrer"
                >https://github.com/YahaayLabs/laravel-barangay-search</a>
            </header>

            {{ $slot }}

            <p class="gis-demo-footer">
                <a href="https://github.com/YahaayLabs/laravel-barangay-search" target="_blank" rel="noopener noreferrer">GitHub</a>
                ·
                <a href="https://gis.ph" target="_blank" rel="noopener noreferrer">gis.ph</a>
                ·
                <a href="https://packagist.org/packages/yahaaylabs/laravel-barangay-search" target="_blank" rel="noopener noreferrer">Packagist</a>
            </p>
        </div>
    </div>
    @livewireScripts
</body>
</html>

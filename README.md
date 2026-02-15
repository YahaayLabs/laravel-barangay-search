# Laravel Barangay Search

[![Latest Version on Packagist](https://img.shields.io/packagist/v/yahaaylabs/laravel-barangay-search.svg?style=flat-square)](https://packagist.org/packages/yahaaylabs/laravel-barangay-search)
[![Total Downloads](https://img.shields.io/packagist/dt/yahaaylabs/laravel-barangay-search.svg?style=flat-square)](https://packagist.org/packages/yahaaylabs/laravel-barangay-search)

A Laravel Livewire component for searching Philippine Barangays with optional Mary UI support. This package uses the [GIS.PH SDK](https://github.com/yahaaylabs/gis.ph-sdk-php) to interact with the official [GIS.PH API](https://gis.ph).

## Features

- 🔍 **Autocomplete Search** - Real-time Barangay Search with debouncing
- 🎨 **Mary UI Support** - Pre-styled components using Mary UI (optional)
- 💾 **Caching** - Intelligent caching to reduce API calls
- 🎯 **Filtering** - Filter by municipality, city, or province
- 🔧 **Customizable** - Fully customizable UI and behavior
- ⚡ **Fast** - Optimized for performance
- 📦 **Easy Integration** - Drop-in component for any Laravel Livewire app

## Requirements

- PHP 8.1 or higher
- Laravel 10.x or 11.x
- Livewire 3.x
- [GIS.PH API Key](https://gis.ph)

## Installation

### 1. Install via Composer

```bash
composer require yahaaylabs/laravel-barangay-search
```

### 2. Publish Configuration (Optional)

```bash
php artisan vendor:publish --tag=barangay-search-config
```

### 3. Publish Views (Optional)

If you want to customize the views:

```bash
php artisan vendor:publish --tag=barangay-search-views
```

### 4. Set Your API Key

Add your GIS.PH API key to your `.env` file:

```env
GISPH_API_KEY=your_api_key_here
```

Get your API key from [https://gis.ph](https://gis.ph)

## Quick Start

### Basic Usage

```blade
<livewire:barangay-search 
    wire:model="selectedBarangay"
    label="Select Barangay"
    placeholder="Search for a barangay..."
/>
```

### With Form Integration

```blade
<x-form wire:submit="save">
    <x-input label="Name" wire:model="name" />
    
    <livewire:barangay-search 
        wire:model="form.barangay"
        label="Barangay"
        :required="true"
        hint="Start typing to search"
    />
    
    <x-slot:actions>
        <x-button label="Cancel" link="/dashboard" />
        <x-button label="Save" type="submit" spinner="save" />
    </x-slot:actions>
</x-form>
```

### Listening to Events

```blade
<livewire:barangay-search wire:model="barangay" />

@script
<script>
    $wire.on('barangay-selected', (event) => {
        console.log('Selected:', event.barangay);
        // Do something with the selected barangay
    });
    
    $wire.on('barangay-cleared', () => {
        console.log('Selection cleared');
    });
    
    $wire.on('barangay-search-error', (event) => {
        console.error('Search error:', event.error);
    });
</script>
@endscript
```

## Component Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `wire:model` | mixed | `null` | Bind the selected barangay |
| `label` | string | `''` | Label text above the input |
| `placeholder` | string | Config value | Placeholder text for the input |
| `required` | boolean | `false` | Mark field as required |
| `clearable` | boolean | `true` | Show clear button |
| `hint` | string | `''` | Helper text below the input |
| `municipalityCode` | string | `null` | Filter results by municipality code |
| `cityCode` | string | `null` | Filter results by city code |
| `provinceCode` | string | `null` | Filter results by province code |
| `containerClass` | string | `''` | Additional CSS classes for container |
| `inputClass` | string | `''` | Additional CSS classes for input |

## Advanced Usage

### Filtering by Municipality

```blade
<livewire:barangay-search 
    wire:model="barangay"
    :municipality-code="$selectedMunicipalityCode"
    label="Select Barangay"
/>
```

### Filtering by Province

```blade
<livewire:barangay-search 
    wire:model="barangay"
    :province-code="$selectedProvinceCode"
    label="Select Barangay in {{ $provinceName }}"
/>
```

### Custom Styling

```blade
<livewire:barangay-search 
    wire:model="barangay"
    container-class="my-custom-class"
    input-class="custom-input-style"
/>
```

### Using Without Mary UI

If you don't want to use Mary UI styling, update your config:

```php
// config/barangay-search.php
'ui' => [
    'use_mary_ui' => false, // Use vanilla styling
],
```

## Configuration

The configuration file `config/barangay-search.php` provides several customization options:

```php
return [
    'api_key' => env('GISPH_API_KEY'),
    
    'cache' => [
        'enabled' => true,
        'ttl' => 3600, // 1 hour
        'prefix' => 'barangay_search',
    ],
    
    'search' => [
        'min_query_length' => 2,
        'debounce_ms' => 300,
        'max_results' => 20,
    ],
    
    'ui' => [
        'placeholder' => 'Search for a barangay...',
        'no_results_text' => 'No barangays found',
        'loading_text' => 'Searching...',
        'use_mary_ui' => true,
    ],
];
```

## Using the Service Directly

You can also use the `BarangayService` directly in your controllers or other classes:

```php
use YahaayLabs\LaravelBarangaySearch\Services\BarangayService;

class YourController extends Controller
{
    public function __construct(
        protected BarangayService $barangayService
    ) {}
    
    public function search(Request $request)
    {
        $results = $this->barangayService->search(
            query: $request->input('q'),
            filters: [
                'municipality_code' => $request->input('municipality_code')
            ]
        );
        
        return response()->json($results);
    }
    
    public function getByCode(string $code)
    {
        $barangay = $this->barangayService->getByCode($code);
        
        return response()->json($barangay);
    }
    
    public function getByMunicipality(string $municipalityCode)
    {
        $barangays = $this->barangayService->getByMunicipality($municipalityCode);
        
        return response()->json($barangays);
    }
}
```

## Response Format

When a barangay is selected, the component returns an array with the following structure:

```php
[
    'code' => '012345678',
    'name' => 'Barangay Name',
    'municipality' => 'Municipality Name',
    'municipality_code' => '012345',
    'city' => 'City Name', // nullable
    'city_code' => '012345', // nullable
    'province' => 'Province Name',
    'province_code' => '0123',
    'region' => 'Region Name',
    'region_code' => '01',
    'full_address' => 'Barangay Name, Municipality Name, Province Name',
]
```

## Events

The component dispatches the following events:

### `barangay-selected`

Fired when a barangay is selected from the dropdown.

```php
$wire.on('barangay-selected', (event) => {
    console.log(event.barangay); // Selected barangay object
});
```

### `barangay-cleared`

Fired when the selection is cleared.

```php
$wire.on('barangay-cleared', () => {
    // Handle clear action
});
```

### `barangay-search-error`

Fired when a search error occurs.

```php
$wire.on('barangay-search-error', (event) => {
    console.error(event.error); // Error message
});
```

## Caching

The package includes built-in caching to reduce API calls:

```php
// Clear all cached searches
use YahaayLabs\LaravelBarangaySearch\Services\BarangayService;

app(BarangayService::class)->clearCache();
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Security

If you discover any security-related issues, please email security@yahaaylabs.com instead of using the issue tracker.

## Credits

- [YahaayLabs](https://github.com/YahaayLabs)
- Built using [GIS.PH SDK](https://github.com/yahaaylabs/gis.ph-sdk-php)
- Powered by [GIS.PH API](https://gis.ph)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Support

For support, email support@yahaaylabs.com or visit our [documentation](https://docs.yahaaylabs.com).

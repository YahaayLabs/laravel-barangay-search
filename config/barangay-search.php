<?php

return [
    /*
    |--------------------------------------------------------------------------
    | GIS.PH API Key
    |--------------------------------------------------------------------------
    |
    | Your API key from https://gis.ph
    | You can get your API key by signing up at https://gis.ph
    |
    */
    'api_key' => env('GISPH_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Configure caching for Barangay Search results to reduce API calls
    |
    */
    'cache' => [
        'enabled' => env('barangay_search_CACHE', true),
        'ttl' => env('barangay_search_CACHE_TTL', 3600), // 1 hour
        'prefix' => 'barangay_search',
    ],

    /*
    |--------------------------------------------------------------------------
    | Search Configuration
    |--------------------------------------------------------------------------
    |
    | Default search parameters
    |
    */
    'search' => [
        'min_query_length' => 2,
        'debounce_ms' => 300,
        'max_results' => 20,
    ],

    /*
    |--------------------------------------------------------------------------
    | UI Configuration
    |--------------------------------------------------------------------------
    |
    | Customize the appearance and behavior
    |
    */
    'ui' => [
        'placeholder' => 'Search for a barangay...',
        'no_results_text' => 'No barangays found',
        'loading_text' => 'Searching...',
        'use_mary_ui' => true, // Set to false for vanilla styling
    ],
];

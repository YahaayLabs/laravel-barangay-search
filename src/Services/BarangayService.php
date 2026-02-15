<?php

namespace YahaayLabs\LaravelBarangaySearch\Services;

use Illuminate\Support\Facades\Cache;
use GisPh\Client as GisPhClient;
use GisPh\Core\Exceptions\GisPhException;

class BarangayService
{
    protected GisPhClient $client;
    protected bool $cacheEnabled;
    protected int $cacheTtl;
    protected string $cachePrefix;

    public function __construct(?string $apiKey = null)
    {
        $this->client = new GisPhClient(['api_key' => $apiKey ?? config('barangay-search.api_key')]);
        $this->cacheEnabled = config('barangay-search.cache.enabled', true);
        $this->cacheTtl = config('barangay-search.cache.ttl', 3600);
        $this->cachePrefix = config('barangay-search.cache.prefix', 'barangay_search');
    }

    /**
     * Search for barangays by query
     *
     * @param string $query Search term
     * @param array $filters Optional filters (municipality, city, province)
     * @return array
     * @throws GisPhException
     */
    public function search(string $query, array $filters = []): array
    {
        if (strlen($query) < config('barangay-search.search.min_query_length', 2)) {
            return [];
        }

        $cacheKey = $this->getCacheKey($query, $filters);

        if ($this->cacheEnabled) {
            return Cache::remember($cacheKey, $this->cacheTtl, function () use ($query, $filters) {
                return $this->performSearch($query, $filters);
            });
        }

        return $this->performSearch($query, $filters);
    }

    /**
     * Get a specific barangay by code
     *
     * @param string $code Barangay code
     * @return array|null
     */
    public function getByCode(string $code): ?array
    {
        try {
            $cacheKey = "{$this->cachePrefix}_barangay_{$code}";

            if ($this->cacheEnabled) {
                return Cache::remember($cacheKey, $this->cacheTtl, function () use ($code) {
                    return $this->client->barangays()->get($code);
                });
            }

            return $this->client->barangays()->get($code);
        } catch (GisPhException $e) {
            return null;
        }
    }

    /**
     * Get barangays by municipality
     *
     * @param string $municipalityCode
     * @return array
     */
    public function getByMunicipality(string $municipalityCode): array
    {
        try {
            $cacheKey = "{$this->cachePrefix}_municipality_{$municipalityCode}";

            if ($this->cacheEnabled) {
                return Cache::remember($cacheKey, $this->cacheTtl, function () use ($municipalityCode) {
                    return $this->client->barangays()->list([
                        'municipality_code' => $municipalityCode
                    ]);
                });
            }

            return $this->client->barangays()->list([
                'municipality_code' => $municipalityCode
            ]);
        } catch (GisPhException $e) {
            return [];
        }
    }

    /**
     * Perform the actual search
     *
     * @param string $query
     * @param array $filters
     * @return array
     */
    protected function performSearch(string $query, array $filters): array
    {
        try {
            $params = array_merge(['q' => $query], $filters);
            $params['limit'] = config('barangay-search.search.max_results', 20);

            $results = $this->client->barangays()->search($params);

            return $this->formatResults($results);
        } catch (GisPhException $e) {
            throw $e;
        }
    }

    /**
     * Format search results for display
     *
     * @param array $results
     * @return array
     */
    protected function formatResults(array $results): array
    {
        $data = $results['data'] ?? $results;

        return collect($data)->map(function ($barangay) {
            return [
                'code' => $barangay['code'] ?? null,
                'name' => $barangay['name'] ?? '',
                'municipality' => $barangay['municipality']['name'] ?? $barangay['municipality'] ?? '',
                'municipality_code' => $barangay['municipality']['code'] ?? null,
                'city' => $barangay['city']['name'] ?? $barangay['city'] ?? null,
                'city_code' => $barangay['city']['code'] ?? null,
                'province' => $barangay['province']['name'] ?? $barangay['province'] ?? '',
                'province_code' => $barangay['province']['code'] ?? null,
                'region' => $barangay['region']['name'] ?? $barangay['region'] ?? '',
                'region_code' => $barangay['region']['code'] ?? null,
                'full_address' => $this->buildFullAddress($barangay),
            ];
        })->toArray();
    }

    /**
     * Build full address string
     *
     * @param array $barangay
     * @return string
     */
    protected function buildFullAddress(array $barangay): string
    {
        $parts = array_filter([
            $barangay['name'] ?? '',
            $barangay['municipality']['name'] ?? $barangay['city']['name'] ?? $barangay['municipality'] ?? $barangay['city'] ?? '',
            $barangay['province']['name'] ?? $barangay['province'] ?? '',
        ]);

        return implode(', ', $parts);
    }

    /**
     * Generate cache key
     *
     * @param string $query
     * @param array $filters
     * @return string
     */
    protected function getCacheKey(string $query, array $filters): string
    {
        $filterString = http_build_query($filters);
        return "{$this->cachePrefix}_" . md5($query . $filterString);
    }

    /**
     * Clear all cached searches
     *
     * @return void
     */
    public function clearCache(): void
    {
        Cache::flush();
    }
}

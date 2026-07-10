# Demo playground

Checkout-style demo for **`yahaaylabs/laravel-barangay-search`** (Livewire).

## Run

```bash
cd playground
cp .env.template .env   # or .env.example
# edit GISPH_API_KEY=gis_sk_…
composer install
php artisan key:generate
php artisan serve
```

Open **http://127.0.0.1:8000**

| | |
| --- | --- |
| **Package** | path-linked from parent (`../`) |
| **UI** | Vanilla (Mary UI disabled in `config/barangay-search.php`) |
| **Env reference** | [`.env.template`](./.env.template) |
| **GitHub** | https://github.com/YahaayLabs/laravel-barangay-search |

Get a key at [gis.ph](https://gis.ph) / [dashboard.gis.ph](https://dashboard.gis.ph).

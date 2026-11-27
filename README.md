# Laravel Sepomex

[![Total Downloads](https://poser.pugx.org/wafto/laravel-sepomex/downloads)](https://packagist.org/packages/wafto/laravel-sepomex)
[![Latest Stable Version](https://poser.pugx.org/wafto/laravel-sepomex/v/stable)](https://packagist.org/packages/wafto/laravel-sepomex)
[![License](https://poser.pugx.org/wafto/laravel-sepomex/license)](https://packagist.org/packages/wafto/laravel-sepomex)

## Introduction

Laravel Sepomex is a package that provides Mexican postal code ([SEPOMEX](https://www.correosdemexico.gob.mx)) lookup functionality for Laravel applications. It imports postal code data from the official "Correos de México" catalog and provides an API to query settlements by postal code or list all states.

## Requirements

- PHP 8.2+ (8.3+ for Laravel 13)
- Laravel 11, 12, or 13

### Version compatibility

| Package version | Laravel | PHP |
|-----------------|---------|------|
| 5.x | 11, 12, 13 | 8.2+ (8.3+ for Laravel 13) |
| 4.x | 10, 11, 12 | 8.2+ |

If your application runs Laravel 10, use the `v4.1.0` release:

```bash
composer require wafto/laravel-sepomex:^4.1
```

## Installation

Install the package via Composer:

```bash
composer require wafto/laravel-sepomex
```

## Setup

### 1. Publish Configuration

Publish the `sepomex.php` configuration file:

```bash
php artisan vendor:publish --provider="Wafto\Sepomex\SepomexServiceProvider"
```

This will create a configuration file at `config/sepomex.php` where you can customize the `table_name` and `source_file` options.

### 2. Download Source File

Download the official SEPOMEX catalog from [Correos de México](https://www.correosdemexico.gob.mx/SSLServicios/ConsultaCP/CodigoPostal_Exportar.aspx) and save it to your `storage` directory as `cpdescarga.txt`.

The file path should match the `source_file` setting in your configuration.

### 3. Run Migrations

Run the database migrations to create the required table:

```bash
php artisan migrate
```

### 4. Import Data

Import the postal code data using the artisan command:

```bash
php artisan sepomex:import --chunk=50
```

The `--chunk` option controls the batch size for database inserts. This process may take several minutes depending on your system.

## Usage

### Using Dependency Injection (Recommended)

Inject the `SepomexContract` interface into your controllers or services:

```php
use Wafto\Sepomex\Contracts\SepomexContract;

class SepomexController extends Controller
{
    public function postal(SepomexContract $sepomex, string $postal)
    {
        return $sepomex->getByPostal($postal);
    }

    public function states(SepomexContract $sepomex)
    {
        return $sepomex->getStates();
    }
}
```

### Using the Facade

Alternatively, register the Sepomex facade in `config/app.php`:

```php
'aliases' => [
    // ...
    'Sepomex' => Wafto\Sepomex\Facades\Sepomex::class,
]
```

Then use it anywhere in your application:

```php
use Sepomex;

class SepomexController extends Controller
{
    public function postal(string $postal)
    {
        return Sepomex::getByPostal($postal);
    }

    public function states()
    {
        return Sepomex::getStates();
    }
}
```

### Using the Container

You can also resolve the contract from the container:

```php
$sepomex = app(\Wafto\Sepomex\Contracts\SepomexContract::class);
$settlements = $sepomex->getByPostal('06600');
```

## API Methods

| Method | Returns |
|--------|---------|
| `getByPostal(string $postal)` | A list of settlements for the postal code, or an empty array if it does not exist |
| `getStates()` | A list of all Mexican states |

Both methods return plain arrays (not entity objects), so results can be returned from a controller as JSON or cached as-is.

`getByPostal('11590')` returns:

```php
[
    [
        'postal' => '11590',
        'state' => ['id' => 9, 'name' => 'Ciudad de México'],
        'city' => ['id' => 11, 'name' => 'Ciudad de México'],
        'district' => ['id' => 16, 'name' => 'Miguel Hidalgo'],
        'location' => ['type' => 'Colonia', 'name' => 'Anzures'],
    ],
]
```

`state`, `city`, `district` and `location` are `null` when the source data does not provide them.

`getStates()` returns:

```php
[
    ['id' => 1, 'name' => 'Aguascalientes'],
    ['id' => 2, 'name' => 'Baja California'],
    // ...
]
```

## Important Notes

The database is distributed by Correos de México with the following clause:

> *El Catálogo Nacional de Códigos Postales, es elaborado por Correos de México y se proporciona en forma gratuita para uso particular, no estando permitida su comercialización, total o parcial, ni su distribución a terceros bajo ningún concepto.*

The database has also been released under the [LIBRE USO MX](https://datos.gob.mx/libreusomx) license.

## Source

> **"Catálogo Nacional de Códigos Postales"** publicado por Correos de México.
> Disponible en [https://www.correosdemexico.gob.mx/SSLServicios/ConsultaCP/CodigoPostal_Exportar.aspx](https://www.correosdemexico.gob.mx/SSLServicios/ConsultaCP/CodigoPostal_Exportar.aspx)

## License

Laravel Sepomex is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).

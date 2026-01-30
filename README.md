# Laravel Sepomex

[![Total Downloads](https://poser.pugx.org/wafto/laravel-sepomex/downloads)](https://packagist.org/packages/wafto/laravel-sepomex)
[![Latest Stable Version](https://poser.pugx.org/wafto/laravel-sepomex/v/stable)](https://packagist.org/packages/wafto/laravel-sepomex)
[![License](https://poser.pugx.org/wafto/laravel-sepomex/license)](https://packagist.org/packages/wafto/laravel-sepomex)

## Introduction

Laravel Sepomex is a package that provides Mexican postal code ([SEPOMEX](https://www.correosdemexico.gob.mx)) lookup functionality for Laravel applications. It imports postal code data from the official "Correos de México" catalog and provides an API to query settlements by postal code or list all states.

## Requirements

- PHP 8.2+
- Laravel 10, 11, or 12

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

| Method | Description |
|--------|-------------|
| `getByPostal(string $postal)` | Returns all settlements matching the given postal code |
| `getStates()` | Returns a list of all Mexican states |

## Important Notes

The database is distributed by Correos de México with the following clause:

> *El Catálogo Nacional de Códigos Postales, es elaborado por Correos de México y se proporciona en forma gratuita para uso particular, no estando permitida su comercialización, total o parcial, ni su distribución a terceros bajo ningún concepto.*

The database has also been released under the [LIBRE USO MX](https://datos.gob.mx/libreusomx) license.

## Source

> **"Catálogo Nacional de Códigos Postales"** publicado por Correos de México.
> Disponible en [https://www.correosdemexico.gob.mx/SSLServicios/ConsultaCP/CodigoPostal_Exportar.aspx](https://www.correosdemexico.gob.mx/SSLServicios/ConsultaCP/CodigoPostal_Exportar.aspx)

## License

Laravel Sepomex is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).

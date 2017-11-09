# Laravel Sepomex
[![Travis](https://travis-ci.org/wafto/laravel-sepomex.svg)](https://travis-ci.org/wafto/laravel-sepomex)
[![Total Downloads](https://poser.pugx.org/wafto/laravel-sepomex/downloads)](https://packagist.org/packages/wafto/laravel-sepomex)
[![Latest Stable Version](https://poser.pugx.org/wafto/laravel-sepomex/v/stable)](https://packagist.org/packages/wafto/laravel-sepomex)
[![License](https://poser.pugx.org/wafto/laravel-sepomex/license)](https://packagist.org/packages/wafto/laravel-sepomex)

## Introduction

This package provides postal [SEPOMEX](http://www.correosdemexico.com.mx/Paginas/Inicio.aspx)
postal code information (unofficial) for Laravel.

## Installation

You can install this package by running:

```bash
composer require wafto/laravel-sepomex
```

## Setup

In order to setup this package, the next steps are needed.

### 1) Service Provider

If you don't use auto-discovery (Laravel 5.5+), add the SepomexServiceProvider to the
providers array in `config/app.php`.

```php
Aftab\Sepomex\SepomexServiceProvider::class
```

### 2) Configuration file

Publish the `sepomex.php` configuration file under `app/config` using the following command:

```php
php artisan vendor:publish --provider="Aftab\Sepomex\SepomexServiceProvider"
```

### 3) Source file

Download and copy file [datos.gob.mx](https://datos.gob.mx/busca/dataset/catalogo-nacional-de-codigos-postales) in
the `storage` directory as `cpdescarga.txt`.

### 4) Migrate and Import

After configuring the file path and table name, run migrations and run the importer command.

```php
php artisan migrate
php artisan sepomex:import
```

This step might took some time to finish.

## Important Notes

The database is distributed with a restrictive clause on the first line of the file.

>*El Catálogo Nacional de Códigos Postales, es elaborado por Correos de
México y se proporciona en forma gratuita para uso particular, no estando
permitida su comercialización, total o parcial, ni su distribución a
terceros bajo ningún concepto.*


**But** the database has been released under the license [LIBRE USO MX](https://datos.gob.mx/libreusomx)
so hopefully in the future the clause in the file change.

## Source (Fuente)

>***"Catálogo Nacional de Códigos Postales"** publicado por Correos de México. Consultado en
[https://datos.gob.mx/busca/dataset/catalogo-nacional-de-codigos-postales](https://datos.gob.mx/busca/dataset/catalogo-nacional-de-codigos-postales)
el 2017-11-06.*

## License

Laravel Sepomex is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).

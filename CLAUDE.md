# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Laravel Sepomex is a Laravel package that provides Mexican postal code (SEPOMEX) lookup functionality. It imports postal code data from the official "Correos de México" source file and provides an API to query settlements by postal code or list all states.

## Commands

```bash
# Install dependencies
composer install

# Run all tests
composer test

# Run a single test file
vendor/bin/phpunit tests/DatabaseRepositoryTest.php

# Run a single test method
vendor/bin/phpunit --filter testGetByPostal

# Code formatting with Pint
composer pint
```

## Architecture

### Repository Pattern with Caching Decorator

The package uses the decorator pattern for caching:

- `SepomexContract` ([src/Contracts/SepomexContract.php](src/Contracts/SepomexContract.php)) - Interface defining `getByPostal()` and `getStates()`
- `DatabaseRepository` ([src/Repositories/DatabaseRepository.php](src/Repositories/DatabaseRepository.php)) - Direct database queries via Eloquent
- `CachedRepository` ([src/Repositories/CachedRepository.php](src/Repositories/CachedRepository.php)) - Wraps any `SepomexContract` implementation and adds caching

The service provider binds `SepomexContract` to `CachedRepository(DatabaseRepository)`.

### Domain Entities

The `Settlement` entity is the primary data structure returned by queries, containing:
- `State` (id, name)
- `City` (id, name)
- `District` (id, name)
- `Location` (type, name)

All entities implement `Arrayable` for easy serialization.

### Data Import

The `sepomex:import` artisan command reads the SEPOMEX text file (pipe-delimited, iso-8859-1 encoded) and batch-inserts into the database. The `--chunk` option controls batch size.

### Testing

Tests use Orchestra Testbench with SQLite in-memory database. Test data file is at `tests/files/test.txt`.

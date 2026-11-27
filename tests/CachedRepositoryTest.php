<?php

use Illuminate\Contracts\Cache\Repository;
use Wafto\Sepomex\Contracts\SepomexContract;
use Wafto\Sepomex\Models\Sepomex;
use Wafto\Sepomex\Repositories\CachedRepository;
use Wafto\Sepomex\Repositories\DatabaseRepository;

it('returns settlements array by postal code', function () {
    $this->artisan('sepomex:import', ['--chunk' => '50']);

    $repo = new CachedRepository(new DatabaseRepository(new Sepomex), app(Repository::class));
    $arr = $repo->getByPostal('11590');

    expect($arr[0])->toHaveKey('postal')
        ->and($arr[0]['postal'])->toBe('11590');
});

it('returns states array', function () {
    $this->artisan('sepomex:import', ['--chunk' => '50']);

    $repo = new CachedRepository(new DatabaseRepository(new Sepomex), app(Repository::class));
    $states = $repo->getStates();

    expect($states)->toHaveCount(3);
});

it('works with cache stores that do not support tags', function () {
    config(['sepomex.cache.store' => 'file']);
    $this->artisan('sepomex:import', ['--chunk' => '50'])->assertExitCode(0);

    $repo = app(CachedRepository::class);

    expect($repo->getStates())->toHaveCount(3)
        ->and($repo->getByPostal('11590'))->not->toBeEmpty();

    cache()->store('file')->flush();
});

it('serves stale data until flushed', function () {
    $this->artisan('sepomex:import', ['--chunk' => '50']);

    $repo = app(CachedRepository::class);
    expect($repo->getStates())->toHaveCount(3);

    Sepomex::query()->insert(['d_codigo' => '99999', 'c_estado' => 99, 'd_estado' => 'Nuevo']);
    expect($repo->getStates())->toHaveCount(3);

    $repo->flush();
    expect($repo->getStates())->toHaveCount(4);
});

it('flushes the cache after importing', function () {
    $repo = app(CachedRepository::class);
    $repo->getStates();

    expect(cache()->get('sepomex.sepomex.version'))->toBe(1);

    $this->artisan('sepomex:import', ['--chunk' => '50'])->assertExitCode(0);

    expect(cache()->get('sepomex.sepomex.version'))->toBe(2);
});

it('does not query or cache invalid postal codes', function (string $postal) {
    $next = Mockery::mock(SepomexContract::class);
    $next->shouldNotReceive('getByPostal');

    $cache = app(Repository::class);
    $repo = new CachedRepository($next, $cache);

    expect($repo->getByPostal($postal))->toBe([])
        ->and($cache->has("sepomex.sepomex.v1.postal.{$postal}"))->toBeFalse();
})->with(['1159', '115900', 'abcde', ' 11590', '11590 ', '', '1159a', "11590\n"]);

it('caches valid postal codes that do not exist', function () {
    $next = Mockery::mock(SepomexContract::class);
    $next->shouldReceive('getByPostal')->once()->with('99999')->andReturn([]);

    $repo = new CachedRepository($next, app(Repository::class));

    expect($repo->getByPostal('99999'))->toBe([])
        ->and($repo->getByPostal('99999'))->toBe([]);
});

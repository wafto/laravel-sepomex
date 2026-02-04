<?php

use Illuminate\Contracts\Cache\Repository;
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

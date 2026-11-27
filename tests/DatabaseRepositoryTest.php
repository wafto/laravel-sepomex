<?php

use Wafto\Sepomex\Models\Sepomex;
use Wafto\Sepomex\Repositories\DatabaseRepository;

it('returns settlements array by postal code', function () {
    $this->artisan('sepomex:import', ['--chunk' => '50']);

    $repo = new DatabaseRepository(new Sepomex);
    $arr = $repo->getByPostal('11590');

    expect($arr[0])->toHaveKey('postal')
        ->and($arr[0]['postal'])->toBe('11590');
});

it('returns states array', function () {
    $this->artisan('sepomex:import', ['--chunk' => '50']);

    $repo = new DatabaseRepository(new Sepomex);
    $states = $repo->getStates();

    expect($states)->toHaveCount(3);
});

it('returns settlements as plain arrays matching the contract shape', function () {
    $this->artisan('sepomex:import', ['--chunk' => '50']);

    $settlement = (new DatabaseRepository(new Sepomex))->getByPostal('11590')[0];

    expect($settlement)->toBeArray()->toHaveKeys(['postal', 'state', 'city', 'district', 'location'])
        ->and($settlement['state'])->toMatchArray(['id' => 9])
        ->and($settlement['city'])->toMatchArray(['id' => 11])
        ->and($settlement['district'])->toMatchArray(['id' => 16, 'name' => 'Miguel Hidalgo'])
        ->and($settlement['location'])->toBe(['type' => 'Colonia', 'name' => 'Anzures']);
});

it('returns an empty array for unknown postal codes', function () {
    $this->artisan('sepomex:import', ['--chunk' => '50']);

    expect((new DatabaseRepository(new Sepomex))->getByPostal('00000'))->toBe([]);
});

it('returns states as plain id and name arrays', function () {
    $this->artisan('sepomex:import', ['--chunk' => '50']);

    foreach ((new DatabaseRepository(new Sepomex))->getStates() as $state) {
        expect($state)->toBeArray()->toHaveKeys(['id', 'name'])
            ->and($state['id'])->toBeInt();
    }
});

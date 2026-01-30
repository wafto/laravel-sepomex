<?php

use Wafto\Sepomex\Repositories\DatabaseRepository;

it('returns settlements array by postal code', function () {
    $this->artisan('sepomex:import', ['--chunk' => '50']);

    $repo = new DatabaseRepository;
    $arr = $repo->getByPostal('11590');

    expect($arr[0])->toHaveKey('postal')
        ->and($arr[0]['postal'])->toBe('11590');
});

it('returns states array', function () {
    $this->artisan('sepomex:import', ['--chunk' => '50']);

    $repo = new DatabaseRepository;
    $states = $repo->getStates();

    expect($states)->toHaveCount(3);
});

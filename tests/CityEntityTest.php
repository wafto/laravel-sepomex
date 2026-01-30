<?php

use Wafto\Sepomex\Entities\City;

it('has working getters', function () {
    $city = new City(11, 'Ciudad de México');

    expect($city->getId())->toBe(11)
        ->and($city->getName())->toBe('Ciudad de México');
});

it('converts to array', function () {
    $city = new City(11, 'Ciudad de México');

    expect($city->toArray())->toHaveKeys(['id', 'name']);
});

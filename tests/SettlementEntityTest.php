<?php

use Wafto\Sepomex\Entities\City;
use Wafto\Sepomex\Entities\District;
use Wafto\Sepomex\Entities\Location;
use Wafto\Sepomex\Entities\Settlement;
use Wafto\Sepomex\Entities\State;

it('has working setters and getters', function () {
    $state = new State(9, 'Ciudad de México');
    $city = new City(11, 'Ciudad de México');
    $district = new District(16, 'Miguel Hidalgo');
    $location = new Location('Colonia', 'Miguel Hidalgo');
    $settlement = new Settlement;

    $settlement->setPostal('11590');
    $settlement->setState($state);
    $settlement->setCity($city);
    $settlement->setDistrict($district);
    $settlement->setLocation($location);

    expect($settlement->getCity())->toBe($city)
        ->and($settlement->getDistrict())->toBe($district)
        ->and($settlement->getLocation())->toBe($location)
        ->and($settlement->getState())->toBe($state);
});

it('converts to array', function () {
    $state = new State(9, 'Ciudad de México');
    $city = new City(11, 'Ciudad de México');
    $district = new District(16, 'Miguel Hidalgo');
    $location = new Location('Colonia', 'Miguel Hidalgo');
    $settlement = new Settlement;

    $settlement->setPostal('11590');
    $settlement->setState($state);
    $settlement->setCity($city);
    $settlement->setDistrict($district);
    $settlement->setLocation($location);

    expect($settlement->toArray())->toHaveKeys(['state', 'city', 'district', 'location']);
});

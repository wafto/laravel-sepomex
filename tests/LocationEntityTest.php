<?php

use Wafto\Sepomex\Entities\Location;

it('has working getters', function () {
    $location = new Location('Colonia', 'Miguel Hidalgo');

    expect($location->getType())->toBe('Colonia')
        ->and($location->getName())->toBe('Miguel Hidalgo');
});

it('converts to array', function () {
    $location = new Location('Colonia', 'Miguel Hidalgo');

    expect($location->toArray())->toHaveKeys(['type', 'name']);
});

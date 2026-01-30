<?php

use Wafto\Sepomex\Entities\District;

it('has working getters', function () {
    $district = new District(16, 'Miguel Hidalgo');

    expect($district->getId())->toBe(16)
        ->and($district->getName())->toBe('Miguel Hidalgo');
});

it('converts to array', function () {
    $district = new District(16, 'Miguel Hidalgo');

    expect($district->toArray())->toHaveKeys(['id', 'name']);
});

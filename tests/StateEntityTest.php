<?php

use Wafto\Sepomex\Entities\State;

it('has working getters', function () {
    $state = new State(9, 'Ciudad de México');

    expect($state->getId())->toBe(9)
        ->and($state->getName())->toBe('Ciudad de México');
});

it('converts to array', function () {
    $state = new State(9, 'Ciudad de México');

    expect($state->toArray())->toHaveKeys(['id', 'name']);
});

<?php

use Wafto\Sepomex\Facades\Sepomex;

it('gets settlements by postal code via facade', function () {
    $this->artisan('sepomex:import', ['--chunk' => '50']);

    $arr = Sepomex::getByPostal('11590');

    expect($arr[0])->toHaveKey('postal')
        ->and($arr[0]['postal'])->toBe('11590');
});

it('gets states array via facade', function () {
    $this->artisan('sepomex:import', ['--chunk' => '50']);

    $states = Sepomex::getStates();

    expect($states)->toHaveCount(3);
});

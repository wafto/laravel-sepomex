<?php

use Wafto\Sepomex\Entities\Settlement;
use Wafto\Sepomex\Models\Sepomex;

it('connects to the configured table', function () {
    $model = new Sepomex;

    expect($model->getTable())->toBe(config('sepomex.table_name'));
});

it('queries by postal code', function () {
    $this->artisan('sepomex:import', ['--chunk' => '50']);

    $model = Sepomex::postalCode('11590')->first();

    expect($model)->not->toBeNull();

    $collection = Sepomex::postalCode('67200')->get();

    expect($collection)->toHaveCount(6)
        ->and($model->toEntity())->toBeInstanceOf(Settlement::class);
});

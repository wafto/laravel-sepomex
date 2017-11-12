<?php

namespace Aftab\Sepomex\Tests;

use Aftab\Sepomex\Entities\City;

/**
 * Class CityEntityTest
 * @package Aftab\Sepomex\Tests
 */
class CityEntityTest extends TestCase
{
    /** @test */
    public function entity_city_setters_and_getters()
    {
        $city = new City(11, 'Ciudad de México');

        $this->assertEquals(11, $city->getId());
        $this->assertEquals('Ciudad de México', $city->getName());
    }

    /** @test */
    public function entity_city_to_array()
    {
        $city = new City(11, 'Ciudad de México');

        $this->assertArrayHasKey('id', $city->toArray());
        $this->assertArrayHasKey('name', $city->toArray());
    }
}

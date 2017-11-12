<?php

namespace Aftab\Sepomex\Tests;

use Aftab\Sepomex\Entities\Location;

/**
 * Class LocationEntityTest
 * @package Aftab\Sepomex\Tests
 */
class LocationEntityTest extends TestCase
{
    /** @test */
    public function entity_location_setters_and_getters()
    {
        $location = new Location('Colonia', 'Miguel Hidalgo');

        $this->assertEquals('Colonia', $location->getType());
        $this->assertEquals('Miguel Hidalgo', $location->getName());
    }

    /** @test */
    public function entity_location_to_array()
    {
        $location = new Location('Colonia', 'Miguel Hidalgo');

        $this->assertArrayHasKey('type', $location->toArray());
        $this->assertArrayHasKey('name', $location->toArray());
    }
}
